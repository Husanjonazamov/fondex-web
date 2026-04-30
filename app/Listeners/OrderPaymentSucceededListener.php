<?php

namespace App\Listeners;

use App\Events\OrderPaymentSucceeded;
use App\Services\FirestoreService;
use Illuminate\Support\Facades\Log;

class OrderPaymentSucceededListener
{
    public function handle(OrderPaymentSucceeded $event)
    {
        $order       = $event->order;
        $transaction = $event->transaction;

        $collection = $order->firebase_collection ?? null;
        $firebaseId = $order->firebase_order_id   ?? null;

        if (!$collection || !$firebaseId) {
            Log::warning('OrderPaymentSucceeded: firebase_collection yoki firebase_order_id yo\'q', [
                'payme_order_id' => $order->id,
            ]);
            return;
        }

        Log::info('OrderPaymentSucceeded: Firebase order yangilanmoqda', [
            'payme_order_id' => $order->id,
            'collection'     => $collection,
            'firebase_id'    => $firebaseId,
            'amount'         => $order->amount / 100,
        ]);

        $firestore = new FirestoreService();
        $success   = $firestore->markOrderAsPaid($collection, $firebaseId);

        if ($success) {
            Log::info('OrderPaymentSucceeded: Firebase order to\'langan deb belgilandi', [
                'collection' => $collection,
                'firebase_id' => $firebaseId,
            ]);
        } else {
            Log::error('OrderPaymentSucceeded: Firebase order yangilashda xato', [
                'collection' => $collection,
                'firebase_id' => $firebaseId,
            ]);
        }
    }
}
