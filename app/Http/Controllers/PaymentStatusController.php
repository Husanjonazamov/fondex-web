<?php

namespace App\Http\Controllers;

use JscorpTech\Payme\Models\Order;
use JscorpTech\Payme\Models\Transaction;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentStatusController extends Controller
{
    /**
     * To'lov statusini tekshirish API
     * Transaction jadvalidan ham tekshiradi va order ni sinxronlaydi
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            
            if (!$orderId) {
                return response()->json([
                    'success' => false,
                    'message' => 'order_id majburiy'
                ], 400);
            }

            // Payme order ni topish
            $order = Order::find($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buyurtma topilmadi',
                    'payment_status' => 'not_found'
                ], 404);
            }

            // Transaction jadvalidan tekshirish - callback kelmagan bo'lsa ham ishlaydi
            $transaction = Transaction::where('order_id', $orderId)
                ->orderBy('id', 'desc')
                ->first();
            
            // Agar transaction completed (state=2) va order hali yangilanmagan bo'lsa
            if ($transaction && $transaction->state == 2 && $order->state != 2) {
                Log::info('Syncing order state from transaction', [
                    'order_id' => $orderId,
                    'transaction_state' => $transaction->state,
                    'order_state' => $order->state
                ]);
                
                // Order ni yangilash
                $this->syncOrderFromTransaction($order, $transaction);
                
                // Order ni qayta yuklash
                $order->refresh();
            }

            // To'lov statusini aniqlash (state: 0=pending, 1=processing, 2=paid, -1=cancelled)
            $status = match($order->state) {
                0 => 'pending',
                1 => 'processing',
                2 => 'paid',
                -1, -2 => 'cancelled',
                default => 'unknown'
            };
            
            $isPaid = $order->state == 2;
            
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'payment_status' => $status,
                'is_paid' => $isPaid,
                'amount' => $order->amount / 100, // tiyindan so'mga
                'user_id' => $order->user_id,
                'transaction_id' => $transaction?->id,
                'transaction_state' => $transaction?->state,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ], 200);

        } catch (\Exception $e) {
            Log::error('Payment Status Check Error', [
                'error' => $e->getMessage(),
                'order_id' => $request->input('order_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi'
            ], 500);
        }
    }
    
    /**
     * Transaction asosida order ni sinxronlash
     */
    private function syncOrderFromTransaction($order, $transaction)
    {
        try {
            // Order holatini yangilash
            $order->state = 2; // To'langan
            $order->save();
            
            Log::info('Order synced from transaction', [
                'order_id' => $order->id,
                'transaction_id' => $transaction->id,
                'new_state' => $order->state
            ]);
            
            // Foydalanuvchi balansini to'ldirish (wallet uchun)
            if (isset($order->type) && $order->type === 'wallet' && isset($order->user_id)) {
                $user = User::find($order->user_id);
                if ($user) {
                    $amountInSum = $order->amount / 100;
                    $user->wallet_balance = ($user->wallet_balance ?? 0) + $amountInSum;
                    $user->save();

                    Log::info('Wallet Balance Updated via sync', [
                        'user_id' => $user->id,
                        'amount' => $amountInSum,
                        'new_balance' => $user->wallet_balance
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Sync order from transaction failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Foydalanuvchi barcha to'lovlarini olish
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPayments(Request $request)
    {
        try {
            $phone = $request->input('phone');
            
            if (!$phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'phone majburiy'
                ], 400);
            }

            // Telefon orqali user topish
            $user = \App\Models\User::where('email', $phone)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foydalanuvchi topilmadi'
                ], 404);
            }

            $payments = Order::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($order) {
                    $status = match($order->state) {
                        0 => 'pending',
                        1 => 'processing',
                        2 => 'paid',
                        -1, -2 => 'cancelled',
                        default => 'unknown'
                    };
                    
                    return [
                        'order_id' => $order->id,
                        'payment_status' => $status,
                        'is_paid' => $order->state == 2,
                        'amount' => $order->amount / 100,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at
                    ];
                });

            return response()->json([
                'success' => true,
                'count' => $payments->count(),
                'payments' => $payments
            ], 200);

        } catch (\Exception $e) {
            Log::error('Get User Payments Error', [
                'error' => $e->getMessage(),
                'phone' => $request->input('phone')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi'
            ], 500);
        }
    }
}

