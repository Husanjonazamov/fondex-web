<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Models\VendorUsers;
use App\Services\FirestoreService;
use Illuminate\Support\Facades\Log;

class PaymentSucceededListener
{
    public function handle(PaymentSucceeded $event)
    {
        $order  = $event->order;
        $user   = $event->user;
        $amount = $order->amount / 100; // tiyin → so'm

        if (!$user) {
            Log::warning('PaymentSucceeded: user topilmadi', ['order_id' => $order->id]);
            return;
        }

        $phone     = $user->email;
        $firestore = new FirestoreService();

        // 1. Firebase Auth dan telefon orqali UID topishga harakat
        $uid = $firestore->getUidByPhone($phone);

        // 2. Topilmasa — vendor_users.uuid dan olamiz (Flutter login da saqlanadi)
        if (!$uid) {
            Log::info('PaymentSucceeded: Auth da topilmadi, vendor_users.uuid ishlatilmoqda', ['phone' => $phone]);
            $vendorUser = VendorUsers::where('user_id', $user->id)->first();
            $uid = $vendorUser?->uuid;
        }

        if (!$uid) {
            Log::error('PaymentSucceeded: Firebase UID hech qayerdan topilmadi', ['phone' => $phone, 'user_id' => $user->id]);
            return;
        }

        Log::info('PaymentSucceeded: Firestore wallet_amount yangilanmoqda', [
            'uid'    => $uid,
            'phone'  => $phone,
            'amount' => $amount,
        ]);

        $success = $firestore->incrementWalletAmount($uid, $amount);

        if ($success) {
            Log::info('PaymentSucceeded: wallet_amount yangilandi', [
                'uid'   => $uid,
                'added' => $amount,
            ]);
        } else {
            Log::error('PaymentSucceeded: wallet_amount yangilashda xato', ['uid' => $uid]);
        }
    }
}
