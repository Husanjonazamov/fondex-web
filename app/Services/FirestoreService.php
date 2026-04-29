<?php

namespace App\Services;

use Google\Client as Google_Client;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FirestoreService
{
    private string $projectId;
    private string $baseUrl;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->baseUrl   = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }

    /**
     * Firestore uchun Google OAuth2 access token olish
     */
    private function getAccessToken(): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $credentialsPath = storage_path('app/firebase/credentials.json');

        if (!file_exists($credentialsPath)) {
            Log::error('FirestoreService: credentials.json topilmadi', ['path' => $credentialsPath]);
            return null;
        }

        try {
            $client = new Google_Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/datastore');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
            $this->accessToken = $token['access_token'] ?? null;
            return $this->accessToken;
        } catch (\Exception $e) {
            Log::error('FirestoreService: access token olishda xato', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Firestore da mavjud hujjatning bitta maydonini atomik ko'paytirish (increment)
     * Agar hujjat yoki maydon mavjud bo'lmasa ham ishlaydi
     *
     * @param string $collection   Masalan: "users"
     * @param string $documentId   Masalan: Firebase UID
     * @param string $field        Masalan: "balance"
     * @param float  $amount       Qo'shiladigan miqdor
     */
    public function incrementField(string $collection, string $documentId, string $field, float $amount): bool
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $documentPath = "projects/{$this->projectId}/databases/(default)/documents/{$collection}/{$documentId}";
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents:commit";

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
            $client = new Client(['timeout' => 10, 'connect_timeout' => 5]);
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $body,
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                Log::info('FirestoreService: increment muvaffaqiyatli', [
                    'collection' => $collection,
                    'document'   => $documentId,
                    'field'      => $field,
                    'amount'     => $amount,
                ]);
                return true;
            }

            Log::warning('FirestoreService: kutilmagan status', ['status' => $statusCode]);
            return false;

        } catch (\Exception $e) {
            Log::error('FirestoreService: increment xatosi', [
                'collection' => $collection,
                'document'   => $documentId,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }
}
