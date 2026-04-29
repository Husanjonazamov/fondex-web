<?php

namespace App\Services;

use Google\Client as Google_Client;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirebaseRTDBService
{
    private string $databaseUrl;
    private string $credentialsPath;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->databaseUrl     = rtrim(env('FIREBASE_DATABASE_URL', ''), '/');
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
     * RTDB dagi son maydonni oshirish (read → add → write)
     *
     * @param string $path    Masalan: "users/UID/balance"
     * @param float  $amount  Qo'shiladigan miqdor
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
            // Hozirgi qiymatni o'qish
            $getResp = $client->get($url);
            $current = (float) json_decode($getResp->getBody()->getContents(), true);

            $newValue = $current + $amount;

            // Yangi qiymat yozish
            $putResp = $client->put($url, [
                'json' => $newValue,
            ]);

            if ($putResp->getStatusCode() === 200) {
                Log::info('FirebaseRTDB: increment muvaffaqiyatli', [
                    'path'      => $path,
                    'was'       => $current,
                    'added'     => $amount,
                    'now'       => $newValue,
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
