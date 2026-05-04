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
        $collection  = $order->firebase_collection ?? null;
        $firebaseId  = $order->firebase_order_id   ?? null;

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

        if (!$success) {
            Log::error('OrderPaymentSucceeded: Firebase order yangilashda xato', [
                'collection' => $collection,
                'firebase_id' => $firebaseId,
            ]);
            return;
        }

        Log::info('OrderPaymentSucceeded: Firebase order to\'langan deb belgilandi', [
            'collection'  => $collection,
            'firebase_id' => $firebaseId,
        ]);

        if ($collection === 'vendor_orders') {
            $this->handleVendorOrder($firestore, $firebaseId);
        }
    }

    private function handleVendorOrder(FirestoreService $firestore, string $firebaseId): void
    {
        $firebaseOrder = $firestore->getDocument('vendor_orders', $firebaseId);

        if (!$firebaseOrder) {
            Log::error('OrderPaymentSucceeded: vendor_orders document topilmadi', [
                'firebase_id' => $firebaseId,
            ]);
            return;
        }

        // 1. Vendor balansini oshirish
        $vendorUserId = $firebaseOrder['vendor']['author']
            ?? $firebaseOrder['vendor']['authorID']
            ?? null;

        $vendorAmount = $this->calculateProductsTotal($firebaseOrder['products'] ?? []);

        if ($vendorUserId && $vendorAmount > 0) {
            if ($firestore->incrementWalletAmount($vendorUserId, $vendorAmount)) {
                Log::info('OrderPaymentSucceeded: vendor balance oshirildi', [
                    'firebase_id'    => $firebaseId,
                    'vendor_user_id' => $vendorUserId,
                    'amount'         => $vendorAmount,
                ]);
            }
        }

        // 2. Vendorga FCM notification yuborish
        if ($vendorUserId) {
            $sent = $firestore->sendFcmNotification(
                $vendorUserId,
                'Yangi buyurtma!',
                'Payme orqali yangi buyurtma keldi. Iltimos tekshiring.'
            );
            Log::info('OrderPaymentSucceeded: vendor notification', [
                'firebase_id'    => $firebaseId,
                'vendor_user_id' => $vendorUserId,
                'sent'           => $sent,
            ]);
        }

        // 3. Usrega FCM notification yuborish (to'lov tasdiqlandi)
        $userUid = $firebaseOrder['authorID'] ?? ($firebaseOrder['author']['id'] ?? null);
        if ($userUid && $userUid !== 'unknown') {
            $sent = $firestore->sendFcmNotification(
                $userUid,
                'Buyurtma tasdiqlandi!',
                'To\'lovingiz qabul qilindi va buyurtmangiz tasdiqlandi.'
            );
            Log::info('OrderPaymentSucceeded: user notification', [
                'firebase_id' => $firebaseId,
                'user_uid'    => $userUid,
                'sent'        => $sent,
            ]);
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
            $price       = $this->toFloat($product['price'] ?? 0);
            $quantity    = (int) ($product['quantity'] ?? 1);
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
