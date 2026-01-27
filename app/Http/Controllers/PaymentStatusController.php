<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
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
            $paymentId = $request->input('payment_id');
            
            if (!$paymentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'payment_id majburiy'
                ], 400);
            }

            // Payment request ni topish
            $paymentRequest = PaymentRequest::where('id', $paymentId)->first();
            
            if (!$paymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'To\'lov topilmadi',
                    'payment_status' => 'not_found'
                ], 404);
            }

            // To'lov statusini aniqlash
            $isPaid = $paymentRequest->is_paid == 1;
            
            return response()->json([
                'success' => true,
                'payment_id' => $paymentRequest->id,
                'payment_status' => $isPaid ? 'paid' : 'pending',
                'is_paid' => $isPaid,
                'amount' => $paymentRequest->payment_amount ?? 0,
                'payment_method' => $paymentRequest->payment_method ?? null,
                'created_at' => $paymentRequest->created_at,
                'updated_at' => $paymentRequest->updated_at
            ], 200);

        } catch (\Exception $e) {
            Log::error('Payment Status Check Error', [
                'error' => $e->getMessage(),
                'payment_id' => $request->input('payment_id')
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
            $userId = $request->input('user_id');
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'user_id majburiy'
                ], 400);
            }

            $payments = PaymentRequest::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($payment) {
                    return [
                        'payment_id' => $payment->id,
                        'payment_status' => $payment->is_paid == 1 ? 'paid' : 'pending',
                        'is_paid' => $payment->is_paid == 1,
                        'amount' => $payment->payment_amount ?? 0,
                        'payment_method' => $payment->payment_method ?? null,
                        'created_at' => $payment->created_at,
                        'updated_at' => $payment->updated_at
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
                'user_id' => $request->input('user_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi'
            ], 500);
        }
    }
}
