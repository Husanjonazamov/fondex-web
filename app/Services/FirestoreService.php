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

    public function __construct()
    {
        $this->projectId       = env('FIREBASE_PROJECT_ID', '');
        $this->credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase/credentials.json'));
    }

    private function getAccessToken(): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        if (!file_exists($this->credentialsPath)) {
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
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $documentPath = "projects/{$this->projectId}/databases/(default)/documents/users/{$uid}";
        $url          = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents:commit";

        $body = [
            'writes' => [
                [
                    'transform' => [
                        'document'        => $documentPath,
                        'fieldTransforms' => [
                            [
                                'fieldPath' => 'wallet_amount',
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
                Log::info('FirestoreService: wallet_amount yangilandi', [
                    'uid'    => $uid,
                    'added'  => $amount,
                ]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('FirestoreService: wallet_amount xatosi', [
                'uid'   => $uid,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
