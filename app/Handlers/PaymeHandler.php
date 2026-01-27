<?php

namespace App\Handlers;

use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymeHandler
{
    /**
     * To'lov muvaffaqiyatli amalga oshirilganda chaqiriladi
     */
    public static function success($transaction)
    {
        Log::info('Payme Payment Success Handler START', [
            'transaction_id' => $transaction->id,
            'order_id' => $transaction->order_id,
            'state' => $transaction->state
        ]);

        try {
            DB::beginTransaction();

            $order = $transaction->order;
            
            // Order holatini yangilash
            $order->state = 1; // To'langan
            $order->save();

            // Payment request ni topish va yangilash
            $paymentRequest = PaymentRequest::where('order_id', $order->id)->first();
            
            if ($paymentRequest) {
                $paymentRequest->is_paid = 1;
                $paymentRequest->payment_method = 'payme';
                $paymentRequest->save();

                // Foydalanuvchi balansini to'ldirish (wallet uchun)
                if ($order->type === 'wallet' && isset($order->user_id)) {
                    $user = User::find($order->user_id);
                    if ($user) {
                        // Amount tiyin formatida, so'mga o'tkazish
                        $amountInSum = $order->amount / 100;
                        
                        // Balansni yangilash
                        $user->wallet_balance = ($user->wallet_balance ?? 0) + $amountInSum;
                        $user->save();

                        Log::info('Payme Wallet Balance Updated', [
                            'user_id' => $user->id,
                            'amount' => $amountInSum,
                            'new_balance' => $user->wallet_balance
                        ]);
                    }
                }

                Log::info('Payme Payment Request Updated', [
                    'payment_request_id' => $paymentRequest->id,
                    'user_id' => $paymentRequest->user_id ?? null
                ]);
            }

            DB::commit();

            Log::info('Payme Payment Success Handler COMPLETED', [
                'transaction_id' => $transaction->id,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Payme Payment Success Handler ERROR', [
                'transaction_id' => $transaction->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * To'lov bekor qilinganda chaqiriladi
     */
    public static function cancel($transaction)
    {
        Log::info('Payme Payment Cancel Handler START', [
            'transaction_id' => $transaction->id,
            'order_id' => $transaction->order_id,
            'reason' => $transaction->reason
        ]);

        try {
            DB::beginTransaction();

            $order = $transaction->order;
            
            // Order holatini bekor qilish
            $order->state = 2; // Bekor qilingan
            $order->save();

            // Agar to'lov allaqachon amalga oshirilgan bo'lsa va keyin bekor qilingan bo'lsa
            // balansdan ayirish kerak (refund)
            if ($transaction->perform_time && $order->type === 'wallet' && isset($order->user_id)) {
                $user = User::find($order->user_id);
                if ($user) {
                    $amountInSum = $order->amount / 100;
                    $user->wallet_balance = ($user->wallet_balance ?? 0) - $amountInSum;
                    $user->save();

                    Log::info('Payme Wallet Balance Refunded', [
                        'user_id' => $user->id,
                        'amount' => $amountInSum,
                        'new_balance' => $user->wallet_balance
                    ]);
                }
            }

            DB::commit();

            Log::info('Payme Payment Cancel Handler COMPLETED', [
                'transaction_id' => $transaction->id,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Payme Payment Cancel Handler ERROR', [
                'transaction_id' => $transaction->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
