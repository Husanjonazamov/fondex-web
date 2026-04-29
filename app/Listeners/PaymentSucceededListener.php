<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Models\VendorUsers;
use App\Services\FirebaseRTDBService;
use Illuminate\Support\Facades\Log;

class PaymentSucceededListener
{
    public function handle(PaymentSucceeded $event)
    {
        $order       = $event->order;
        $transaction = $event->transaction;
        $user        = $event->user;

        $amountInSum = $order->amount / 100; // tiyin → so'm

        if (!$user) {
            Log::warning('PaymentSucceeded: user topilmadi, Firebase skip', ['order_id' => $order->id]);
            return;
        }

        // Firebase UID ni vendor_users jadvalidan olamiz
        $vendorUser = VendorUsers::where('user_id', $user->id)->first();

        if (!$vendorUser || empty($vendorUser->uuid)) {
            Log::warning('PaymentSucceeded: Firebase UID (uuid) topilmadi', ['user_id' => $user->id]);
            return;
        }

        $firebaseUid = $vendorUser->uuid;

        Log::info('PaymentSucceeded: Firebase balance yangilanmoqda', [
            'firebase_uid' => $firebaseUid,
            'amount'       => $amountInSum,
        ]);

        // Realtime Database: users/{uid}/balance maydonini oshirish
        $rtdb    = new FirebaseRTDBService();
        $success = $rtdb->increment("users/{$firebaseUid}/balance", $amountInSum);

        if ($success) {
            Log::info('PaymentSucceeded: Firebase balance yangilandi', [
                'firebase_uid' => $firebaseUid,
                'added'        => $amountInSum,
            ]);
        } else {
            Log::error('PaymentSucceeded: Firebase balance yangilashda xato', [
                'firebase_uid' => $firebaseUid,
                'amount'       => $amountInSum,
            ]);
        }
    }
}
