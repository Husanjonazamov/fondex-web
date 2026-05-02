<?php

namespace App\Services;

use Google\Client as Google_Client;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirestoreService
{
    private string $projectId;
    private string $credentialsPath;
    private ?string $accessToken = null;
    private ?string $lastError = null;

    public function __construct()
    {
        $this->projectId       = env('FIREBASE_PROJECT_ID', '');
        $this->credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase/credentials.json'));
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    private function getAccessToken(): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        if (!file_exists($this->credentialsPath)) {
            $this->lastError = 'Firebase credentials topilmadi: ' . $this->credentialsPath;
            Log::error('FirestoreService: credentials topilmadi', ['path' => $this->credentialsPath]);
            return null;
        }

        try {
            $client = new Google_Client();
            $client->setAuthConfig($this->credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase');
            $client->addScope('https://www.googleapis.com/auth/userinfo.email');
            $client->addScope('https://www.googleapis.com/auth/cloud-platform');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
            $this->accessToken = $token['access_token'] ?? null;
            return $this->accessToken;
        } catch (\Exception $e) {
            $this->lastError = 'Firebase token xatosi: ' . $e->getMessage();
            Log::error('FirestoreService: token xatosi', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Firestore users collectionidan telefon raqami orqali UID olish
     * Telefon formatlari: "943015498", "+998943015498", "998943015498"
     */
    public function getUidByPhone(string $phone): ?string
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        // Barcha mumkin bo'lgan formatlarni sinab ko'ramiz
        $digits  = preg_replace('/\D/', '', $phone);          // 998943015498
        $short   = preg_replace('/^998/', '', $digits);       // 943015498
        $formats = array_unique([$short, $digits, '+' . $digits]);

        $url    = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents:runQuery";
        $client = new Client(['timeout' => 10, 'connect_timeout' => 5]);

        foreach ($formats as $fmt) {
            try {
                $response = $client->post($url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'structuredQuery' => [
                            'from'  => [['collectionId' => 'users']],
                            'where' => [
                                'fieldFilter' => [
                                    'field' => ['fieldPath' => 'phoneNumber'],
                                    'op'    => 'EQUAL',
                                    'value' => ['stringValue' => $fmt],
                                ],
                            ],
                            'limit' => 1,
                        ],
                    ],
                ]);

                $results = json_decode($response->getBody()->getContents(), true);

                foreach ($results as $result) {
                    $name = $result['document']['name'] ?? null;
                    if ($name) {
                        $uid = basename($name);
                        Log::info('FirestoreService: UID topildi', ['phone' => $fmt, 'uid' => $uid]);
                        return $uid;
                    }
                }

            } catch (\Exception $e) {
                Log::warning('FirestoreService: getUidByPhone xatosi', ['fmt' => $fmt, 'error' => $e->getMessage()]);
            }
        }

        Log::error('FirestoreService: Firestore da user topilmadi', ['phone' => $phone]);
        return null;
    }

    /**
     * Firestore da users/{uid}.wallet_amount ni atomik oshirish
     */
    public function incrementWalletAmount(string $uid, float $amount): bool
    {
        return $this->incrementField('users', $uid, 'wallet_amount', $amount);
    }

    /**
     * Firestore documentidagi numeric fieldni atomik oshirish.
     */
    public function incrementField(string $collection, string $documentId, string $field, float $amount): bool
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $documentPath = "projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}";
        $url          = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents:commit";

        $body = [
            'writes' => [
                [
                    'transform' => [
                        'document'        => $documentPath,
                        'fieldTransforms' => [
                            [
                                'fieldPath' => $field,
                                'increment' => ['doubleValue' => $amount],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $client   = new Client(['timeout' => 10, 'connect_timeout' => 5]);
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $body,
            ]);

            if ($response->getStatusCode() === 200) {
                Log::info('FirestoreService: field increment qilindi', [
                    'collection' => $collection,
                    'document'   => $documentId,
                    'field'      => $field,
                    'added'      => $amount,
                ]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('FirestoreService: incrementField xatosi', [
                'collection' => $collection,
                'document'   => $documentId,
                'field'      => $field,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Firestore da bitta documentni olish (oddiy PHP array qaytaradi)
     */
    public function getDocument(string $collection, string $documentId): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}";

        try {
            $client   = new Client(['timeout' => 10, 'connect_timeout' => 5]);
            $response = $client->get($url, [
                'headers' => ['Authorization' => 'Bearer ' . $token],
            ]);
            $doc = json_decode($response->getBody()->getContents(), true);
            return $this->decodeFirestoreFields($doc['fields'] ?? []);
        } catch (\Exception $e) {
            Log::error('FirestoreService: getDocument xatosi', [
                'collection' => $collection,
                'id'         => $documentId,
                'error'      => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Firestore users/{uid} dan user ma'lumotlarini olish
     */
    public function getUserByUid(string $uid): ?array
    {
        return $this->getDocument('users', $uid);
    }

    /**
     * Firestore collection ichidan bitta string field bo'yicha document olish.
     */
    public function getFirstDocumentByField(string $collection, string $field, string $value): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents:runQuery";

        try {
            $client   = new Client(['timeout' => 10, 'connect_timeout' => 5]);
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'structuredQuery' => [
                        'from'  => [['collectionId' => $collection]],
                        'where' => [
                            'fieldFilter' => [
                                'field' => ['fieldPath' => $field],
                                'op'    => 'EQUAL',
                                'value' => ['stringValue' => $value],
                            ],
                        ],
                        'limit' => 1,
                    ],
                ],
            ]);

            $results = json_decode($response->getBody()->getContents(), true);
            foreach ($results as $result) {
                if (isset($result['document']['fields'])) {
                    return $this->decodeFirestoreFields($result['document']['fields']);
                }
            }
        } catch (\Exception $e) {
            Log::error('FirestoreService: getFirstDocumentByField xatosi', [
                'collection' => $collection,
                'field'      => $field,
                'value'      => $value,
                'error'      => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * vendor_orders collectionida yangi order yaratish.
     * $products = [['data' => [...firestore product fields...], 'quantity' => int], ...]
     * Qaytaradi: yaratilgan document ID yoki null
     */
    public function createVendorOrder(
        string $userUid,
        array  $userData,
        string $vendorId,
        array  $products,
        float  $amount,
        float  $deliveryCharge = 0,
        ?string $driverId = null
    ): ?string {
        $this->lastError = null;

        if (!$this->projectId) {
            $this->lastError = 'FIREBASE_PROJECT_ID sozlanmagan';
            Log::error('FirestoreService: FIREBASE_PROJECT_ID sozlanmagan');
            return null;
        }

        $token = $this->getAccessToken();
        if (!$token) {
            $this->lastError = $this->lastError ?: 'Firebase access token olinmadi';
            return null;
        }

        $orderId = (string) \Illuminate\Support\Str::uuid();
        $now     = now()->toIso8601ZuluString();
        $vendorData = $this->getDocument('vendors', $vendorId)
            ?: $this->getFirstDocumentByField('vendors', 'id', $vendorId)
            ?: ['id' => $vendorId];

        $productItems = array_map(function ($item) use ($vendorId) {
            $productData = $item['data'];
            $quantity    = $item['quantity'];
            return $this->toFirestoreValue([
                'id'            => $productData['id'] ?? '',
                'name'          => $productData['name'] ?? '',
                'photo'         => $productData['photo'] ?? '',
                'price'         => (string) ($productData['price'] ?? '0'),
                'discountPrice' => (string) ($productData['disPrice'] ?? '0'),
                'quantity'      => $quantity,
                'vendorID'      => $vendorId,
                'extras'        => [],
                'extras_price'  => '0',
                'category_id'   => $productData['categoryID'] ?? '',
                'variant_info'  => [
                    'variant_id'      => '',
                    'variant_price'   => '',
                    'variant_sku'     => '',
                    'variant_image'   => '',
                    'variant_options' => [],
                ],
            ]);
        }, $products);

        $firstProduct = $products[0]['data'] ?? [];
        $sectionId = $firstProduct['section_id']
            ?? $firstProduct['sectionId']
            ?? $vendorData['section_id']
            ?? $vendorData['sectionId']
            ?? '';

        $authorValue = $this->toFirestoreValue([
            'id'                => $userUid,
            'firstName'         => $userData['firstName'] ?? ($userData['name'] ?? ''),
            'lastName'          => $userData['lastName'] ?? '',
            'email'             => $userData['email'] ?? '',
            'phoneNumber'       => $userData['phoneNumber'] ?? '',
            'profilePictureURL' => $userData['profilePictureURL'] ?? '',
            'fcmToken'          => $userData['fcmToken'] ?? '',
            'role'              => 'customer',
            'active'            => true,
            'wallet_amount'     => (float) ($userData['wallet_amount'] ?? 0),
        ]);

        $body = [
            'fields' => [
                'address'            => $this->toFirestoreValue([
                    'address'   => null,
                    'addressAs' => null,
                    'id'        => null,
                    'isDefault' => null,
                    'landmark'  => null,
                    'locality'  => '',
                    'location'  => [
                        'latitude'  => 0.0,
                        'longitude' => 0.0,
                    ],
                ]),
                'id'                 => ['stringValue'   => $orderId],
                'vendorID'           => ['stringValue'   => $vendorId],
                'authorID'           => ['stringValue'   => $userUid],
                'author'             => $authorValue,
                'vendor'             => $this->toFirestoreValue($vendorData),
                'products'           => ['arrayValue' => ['values' => $productItems]],
                'payment_method'     => ['stringValue'   => 'payme'],
                'paymentStatus'      => ['booleanValue'  => false],
                'status'             => ['stringValue'   => 'Order Placed'],
                'totalAmount'        => ['stringValue'   => (string) $amount],
                'createdAt'          => ['timestampValue' => $now],
                'discount'           => ['doubleValue'   => 0],
                'deliveryCharge'     => ['stringValue'   => (string) $deliveryCharge],
                'tip_amount'         => ['stringValue'   => '0.0'],
                'takeAway'           => ['booleanValue'  => false],
                'notes'              => ['stringValue'   => ''],
                'adminCommission'    => ['stringValue'   => '0'],
                'adminCommissionType' => ['stringValue'  => 'percentage'],
                'couponCode'         => ['stringValue'   => ''],
                'couponId'           => ['stringValue'   => ''],
                'taxSetting'         => ['arrayValue'    => ['values' => []]],
                'tax_label'          => ['stringValue'   => ''],
                'tax'                => ['stringValue'   => '0'],
                'specialDiscount'    => $this->toFirestoreValue([
                    'special_discount'       => 0.0,
                    'specialType'            => '',
                    'special_discount_label' => 0,
                ]),
                'section_id'         => ['stringValue'   => (string) $sectionId],
                'scheduleTime'       => ['nullValue'     => null],
            ],
        ];

        if ($driverId) {
            $body['fields']['driverId'] = ['stringValue' => $driverId];
        }

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/vendor_orders?documentId={$orderId}";

        try {
            $client   = new Client(['timeout' => 10, 'connect_timeout' => 5]);
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $body,
            ]);

            if ($response->getStatusCode() === 200) {
                Log::info('FirestoreService: vendor_orders yaratildi', [
                    'order_id'      => $orderId,
                    'user_uid'      => $userUid,
                    'vendor_id'     => $vendorId,
                    'products_count' => count($products),
                    'amount'        => $amount,
                ]);
                return $orderId;
            }

            Log::error('FirestoreService: createVendorOrder muvaffaqiyatsiz', [
                'status' => $response->getStatusCode(),
                'body'   => $response->getBody()->getContents(),
            ]);
            $this->lastError = 'Firestore createVendorOrder status: ' . $response->getStatusCode();
            return null;

        } catch (\Exception $e) {
            $responseBody = null;
            if (method_exists($e, 'getResponse') && $e->getResponse()) {
                $responseBody = (string) $e->getResponse()->getBody();
            }

            $this->lastError = 'Firestore createVendorOrder xatosi: ' . $e->getMessage();
            Log::error('FirestoreService: createVendorOrder xatosi', [
                'error' => $e->getMessage(),
                'body'  => $responseBody,
            ]);
            return null;
        }
    }

    /**
     * PHP value → Firestore REST field format
     */
    private function toFirestoreValue(mixed $value): array
    {
        if (is_null($value))   return ['nullValue' => null];
        if (is_bool($value))   return ['booleanValue' => $value];
        if (is_int($value))    return ['integerValue' => (string) $value];
        if (is_float($value))  return ['doubleValue' => $value];
        if (is_string($value)) return ['stringValue' => $value];
        if (is_array($value) && array_is_list($value)) {
            return ['arrayValue' => ['values' => array_map([$this, 'toFirestoreValue'], $value)]];
        }
        if (is_array($value)) {
            $fields = [];
            foreach ($value as $k => $v) {
                $fields[$k] = $this->toFirestoreValue($v);
            }
            return ['mapValue' => ['fields' => $fields]];
        }
        return ['stringValue' => (string) $value];
    }

    /**
     * Firestore REST fields → oddiy PHP array
     */
    private function decodeFirestoreFields(array $fields): array
    {
        $result = [];
        foreach ($fields as $key => $value) {
            $type       = array_key_first($value);
            $val        = $value[$type];
            $result[$key] = match ($type) {
                'stringValue', 'integerValue', 'doubleValue', 'booleanValue', 'timestampValue' => $val,
                'nullValue'  => null,
                'mapValue'   => $this->decodeFirestoreFields($val['fields'] ?? []),
                'arrayValue' => array_map(
                    fn($item) => $this->decodeFirestoreFields([$item])[0] ?? null,
                    $val['values'] ?? []
                ),
                default => $val,
            };
        }
        return $result;
    }

    /**
     * Firebase Firestore da orderni "to'langan" deb belgilash
     *
     * @param string $collection   Masalan: "vendor_orders", "ondemand_orders"
     * @param string $documentId   Firebase order document ID
     */
    public function markOrderAsPaid(string $collection, string $documentId): bool
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        // Har bir collection uchun to'g'ri maydon nomlari
        if ($collection === 'cab_booking_orders') {
            // Taxi: paymentStatus (boolean), paymentMethod (string)
            $fields = [
                'paymentStatus' => ['booleanValue' => true],
                'paymentMethod' => ['stringValue'  => 'payme'],
            ];
            $mask = 'paymentStatus&updateMask.fieldPaths=paymentMethod';
        } else {
            // Product (vendor_orders) va boshqalar
            $fields = [
                'paymentStatus'  => ['booleanValue' => true],
                'payment_method' => ['stringValue'  => 'payme'],
            ];
            $mask = 'paymentStatus&updateMask.fieldPaths=payment_method';
        }

        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}"
             . "?updateMask.fieldPaths={$mask}";

        $body = ['fields' => $fields];

        try {
            $client   = new Client(['timeout' => 10, 'connect_timeout' => 5]);
            $response = $client->patch($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $body,
            ]);

            if ($response->getStatusCode() === 200) {
                Log::info('FirestoreService: order to\'langan deb belgilandi', [
                    'collection' => $collection,
                    'order_id'   => $documentId,
                ]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('FirestoreService: markOrderAsPaid xatosi', [
                'collection' => $collection,
                'order_id'   => $documentId,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }

}
