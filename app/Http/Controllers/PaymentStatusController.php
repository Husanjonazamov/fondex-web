<?php

namespace App\Http\Controllers;

use JscorpTech\Payme\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentStatusController extends Controller
{
    /**
     * To'lov statusini tekshirish API
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

