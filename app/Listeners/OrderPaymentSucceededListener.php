<?php

namespace App\Listeners;

use App\Events\OrderPaymentSucceeded;
use App\Services\FirestoreService;
use Illuminate\Support\Facades\Log;

class OrderPaymentSucceededListener
{
    public function handle(OrderPaymentSucceeded $event)
    {
        $order      = $event->order;
        $collection = $order->firebase_collection ?? null;
        $firebaseId = $order->firebase_order_id   ?? null;

        if (!$collection) {
            Log::warning('OrderPaymentSucceeded: firebase_collection yo\'q', [
                'payme_order_id' => $order->id,
            ]);
            return;
        }

        $firestore  = new FirestoreService();
        $paidAmount = $order->amount / 100; // tiyin -> so'm

        // product uchun Firebase order to'lovdan keyin yaratiladi
        if ($collection === 'vendor_orders' && !$firebaseId) {
            $firebaseId = $this->createFirebaseOrderFromPending($order, $firestore, $paidAmount);
            if (!$firebaseId) {
                return;
            }
        } else {
            if (!$firebaseId) {
                Log::warning('OrderPaymentSucceeded: firebase_order_id yo\'q', [
                    'payme_order_id' => $order->id,
                ]);
                return;
            }

            Log::info('OrderPaymentSucceeded: Firebase order yangilanmoqda', [
                'payme_order_id' => $order->id,
                'collection'     => $collection,
                'firebase_id'    => $firebaseId,
                'amount'         => $paidAmount,
            ]);

            $success = $firestore->markOrderAsPaid($collection, $firebaseId);

            if (!$success) {
                Log::error('OrderPaymentSucceeded: Firebase order yangilashda xato', [
                    'collection'  => $collection,
                    'firebase_id' => $firebaseId,
                ]);
                return;
            }

            Log::info('OrderPaymentSucceeded: Firebase order to\'langan deb belgilandi', [
                'collection'  => $collection,
                'firebase_id' => $firebaseId,
            ]);
        }

        if ($collection === 'vendor_orders') {
            $this->handleVendorOrder($firestore, $firebaseId, $paidAmount);
        }
    }

    private function createFirebaseOrderFromPending($order, FirestoreService $firestore, float $paidAmount): ?string
    {
        $rawData = $order->pending_order_data;
        if (!$rawData) {
            Log::error('OrderPaymentSucceeded: pending_order_data yo\'q', [
                'payme_order_id' => $order->id,
            ]);
            return null;
        }

        $data = is_array($rawData) ? $rawData : json_decode($rawData, true);
        if (!$data) {
            Log::error('OrderPaymentSucceeded: pending_order_data parse xatosi', [
                'payme_order_id' => $order->id,
            ]);
            return null;
        }

        $productPrices = array_map(function ($p) {
            $d = $p['data'] ?? [];
            return [
                'name'     => $d['name'] ?? '',
                'price'    => $d['price'] ?? '0',
                'disPrice' => $d['disPrice'] ?? '0',
                'qty'      => $p['quantity'] ?? 1,
            ];
        }, $data['products'] ?? []);

        $latitude  = isset($data['latitude'])  ? (float) $data['latitude']  : null;
        $longitude = isset($data['longitude']) ? (float) $data['longitude'] : null;

        Log::info('OrderPaymentSucceeded: Firebase vendor_order yaratilmoqda (to\'lovdan keyin)', [
            'payme_order_id'           => $order->id,
            'vendor_id'                => $data['vendor_id'] ?? null,
            'totalAmount_soqm'         => $paidAmount,
            'deliveryCharge_soqm'      => $data['delivery_charge'] ?? 0,
            'payme_order_amount_tiyin' => $order->amount,
            'latitude'                 => $latitude,
            'longitude'                => $longitude,
            'products_prices'          => $productPrices,
        ]);

        $firebaseId = $firestore->createVendorOrder(
            $data['user_uid']        ?? 'unknown',
            $data['user_data']       ?? [],
            $data['vendor_id']       ?? '',
            $data['products']        ?? [],
            $paidAmount,
            (float) ($data['delivery_charge'] ?? 0),
            $data['driver_id']       ?? null,
            true,
            $latitude,
            $longitude
        );

        if (!$firebaseId) {
            Log::error('OrderPaymentSucceeded: Firebase vendor_order yaratilmadi', [
                'payme_order_id' => $order->id,
                'firebase_error' => $firestore->getLastError(),
            ]);
            return null;
        }

        // MySQL orderda firebase_order_id ni yangilaymiz
        $order->firebase_order_id = $firebaseId;
        $order->save();

        Log::info('OrderPaymentSucceeded: Firebase vendor_order yaratildi', [
            'payme_order_id' => $order->id,
            'firebase_id'    => $firebaseId,
        ]);

        return $firebaseId;
    }

    private function handleVendorOrder(FirestoreService $firestore, string $firebaseId, float $paidAmount): void
    {
        $firebaseOrder = $firestore->getDocument('vendor_orders', $firebaseId);

        if (!$firebaseOrder) {
            Log::error('OrderPaymentSucceeded: vendor_orders document topilmadi', [
                'firebase_id' => $firebaseId,
            ]);
            return;
        }

        // 1. Vendor balansini oshirish — amount dan delivery_charge ayiriladi
        $vendorUserId   = $firebaseOrder['vendor']['author']
            ?? $firebaseOrder['vendor']['authorID']
            ?? null;
        $deliveryCharge = (float) ($firebaseOrder['deliveryCharge'] ?? 0);
        $vendorAmount   = max(0, $paidAmount - $deliveryCharge);

        if ($vendorUserId && $vendorAmount > 0) {
            if ($firestore->incrementWalletAmount($vendorUserId, $vendorAmount)) {
                Log::info('OrderPaymentSucceeded: vendor balance oshirildi', [
                    'firebase_id'    => $firebaseId,
                    'vendor_user_id' => $vendorUserId,
                    'paid_amount'    => $paidAmount,
                    'delivery'       => $deliveryCharge,
                    'vendor_amount'  => $vendorAmount,
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
            'firebase_id' => $firebaseId,
            'driver_id'   => $firebaseOrder['driverId'] ?? ($firebaseOrder['driver']['id'] ?? null),
            'reason'      => 'mavjud courier flow ishlatadi',
        ]);
    }
}
