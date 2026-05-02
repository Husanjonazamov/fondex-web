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

            if ($collection === 'vendor_orders') {
                $this->creditProductOrderBalances($firestore, $firebaseId);
            }
        } else {
            Log::error('OrderPaymentSucceeded: Firebase order yangilashda xato', [
                'collection' => $collection,
                'firebase_id' => $firebaseId,
            ]);
        }
    }

    private function creditProductOrderBalances(FirestoreService $firestore, string $firebaseId): void
    {
        $firebaseOrder = $firestore->getDocument('vendor_orders', $firebaseId);
        if (!$firebaseOrder) {
            Log::error('OrderPaymentSucceeded: vendor_orders document topilmadi', [
                'firebase_id' => $firebaseId,
            ]);
            return;
        }

        $vendorUserId = $firebaseOrder['vendor']['author']
            ?? $firebaseOrder['vendor']['authorID']
            ?? null;

        $vendorAmount = $this->calculateProductsTotal($firebaseOrder['products'] ?? []);

        if ($vendorUserId && $vendorAmount > 0) {
            if ($firestore->incrementWalletAmount($vendorUserId, $vendorAmount)) {
                Log::info('OrderPaymentSucceeded: vendor balance oshirildi', [
                    'firebase_id'   => $firebaseId,
                    'vendor_user_id' => $vendorUserId,
                    'amount'         => $vendorAmount,
                ]);
            }
        }

        Log::info('OrderPaymentSucceeded: courier balance alohida oshirilmadi', [
            'firebase_id'     => $firebaseId,
            'driver_id'       => $firebaseOrder['driverId'] ?? ($firebaseOrder['driver']['id'] ?? null),
            'delivery_charge' => $firebaseOrder['deliveryCharge'] ?? 0,
            'reason'          => 'mavjud courier flow ishlatadi',
        ]);
    }

    private function calculateProductsTotal(array $products): float
    {
        $total = 0;

        foreach ($products as $product) {
            $price = $this->toFloat($product['price'] ?? 0);
            $quantity = (int) ($product['quantity'] ?? 1);
            $extrasPrice = $this->toFloat($product['extras_price'] ?? 0);

            $total += ($price * $quantity) + $extrasPrice;
        }

        return $total;
    }

    private function toFloat($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        return 0.0;
    }
}
