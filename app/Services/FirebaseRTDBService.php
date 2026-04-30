<?php

namespace App\Services;

use Google\Client as Google_Client;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirebaseRTDBService
{
    private string $databaseUrl;
    private string $projectId;
    private string $credentialsPath;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->databaseUrl     = rtrim(env('FIREBASE_DATABASE_URL', ''), '/');
        $this->projectId       = env('FIREBASE_PROJECT_ID', '');
        $this->credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase/credentials.json'));
    }

    private function getAccessToken(): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        if (!file_exists($this->credentialsPath)) {
            Log::error('FirebaseRTDB: credentials fayli topilmadi', ['path' => $this->credentialsPath]);
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
            Log::error('FirebaseRTDB: token olishda xato', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Firebase Auth dan telefon raqami orqali UID olish
     */
    public function getUidByPhone(string $phone): ?string
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        // +998... formatga keltirish
        $phone = str_starts_with($phone, '+') ? $phone : '+' . $phone;

        try {
            $client   = new Client(['timeout' => 10, 'connect_timeout' => 5]);
            $url      = "https://identitytoolkit.googleapis.com/v1/projects/{$this->projectId}/accounts:lookup";
            $response = $client->post($url, [
                'headers' => ['Authorization' => 'Bearer ' . $token],
                'json'    => ['phoneNumber' => [$phone]],
            ]);

            $data  = json_decode($response->getBody()->getContents(), true);
            $users = $data['users'] ?? [];

            if (empty($users)) {
                Log::warning('FirebaseRTDB: Firebase Auth da user topilmadi', ['phone' => $phone]);
                return null;
            }

            $uid = $users[0]['localId'];
            Log::info('FirebaseRTDB: Firebase UID topildi', ['phone' => $phone, 'uid' => $uid]);
            return $uid;

        } catch (\Exception $e) {
            Log::error('FirebaseRTDB: getUidByPhone xatosi', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * RTDB dagi son maydonni oshirish (read → add → write)
     */
    public function increment(string $path, float $amount): bool
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $url    = "{$this->databaseUrl}/{$path}.json?access_token={$token}";
        $client = new Client(['timeout' => 10, 'connect_timeout' => 5]);

        try {
            $getResp = $client->get($url);
            $current = (float) json_decode($getResp->getBody()->getContents(), true);
            $newValue = $current + $amount;

            $putResp = $client->put($url, ['json' => $newValue]);

            if ($putResp->getStatusCode() === 200) {
                Log::info('FirebaseRTDB: increment muvaffaqiyatli', [
                    'path'  => $path,
                    'was'   => $current,
                    'added' => $amount,
                    'now'   => $newValue,
                ]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('FirebaseRTDB: increment xatosi', [
                'path'  => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
