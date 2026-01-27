<?php

namespace App\Http\Controllers\Payment_Methods;

use Illuminate\Support\Facades\Log;
use JscorpTech\Payme\Enums\ErrorEnum;
use JscorpTech\Payme\Exceptions\PaymeException;
use JscorpTech\Payme\Views\PaymeApiView;

class PaymeMerchantApiView extends PaymeApiView
{
    public function CheckPerformTransaction()
    {
        Log::info('Payme CheckPerformTransaction START', $this->params);

        try {
            $this->merchant->validateParams($this->request_id, $this->params);

            $orderId = $this->params['account'][$this->field] ?? null;
            if (!$orderId) {
                Log::error('Payme Order ID not provided');
                throw new PaymeException($this->request_id, "Order ID missing", ErrorEnum::INVALID_ACCOUNT);
            }

            $order = $this->order::find($orderId);

            if (!$order) {
                Log::error('Payme Order not found', ['order_id' => $orderId]);
                throw new PaymeException($this->request_id, "Order not found", ErrorEnum::INVALID_ACCOUNT);
            }

            if ($order->state != 0) {
                Log::warning('Payme Order invalid state', [
                    'order_id' => $orderId,
                    'state' => $order->state
                ]);
                throw new PaymeException($this->request_id, "Order already processed", ErrorEnum::INVALID_ACCOUNT);
            }

            // Amount tekshiruvi
            $expectedAmount = (int)$order->amount;
            $incomingAmount = (int)($this->params['amount'] ?? 0);

            if ($expectedAmount !== $incomingAmount) {
                Log::warning('Payme Invalid amount', [
                    'order_id' => $orderId,
                    'expected' => $expectedAmount,
                    'incoming' => $incomingAmount
                ]);
                throw new PaymeException($this->request_id, "Invalid amount", ErrorEnum::INVALID_AMOUNT);
            }

            $items = [[
                "title" => "Wallet top-up",
                "price" => $expectedAmount,
                "count" => 1,
                "code" => env('PAYME_IKPU_CODE'),
                "units" => 796,
                "vat_percent" => 0,
                "package_code" => ""
            ]];

            Log::info('Payme CheckPerformTransaction SUCCESS', [
                'order_id' => $orderId,
                'items' => $items
            ]);

            return $this->success([
                "allow" => true,
                "detail" => [
                    "receipt_type" => 0,
                    "items" => $items
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Payme CheckPerformTransaction ERROR', [
                'error' => $e->getMessage(),
                'params' => $this->params
            ]);

            if ($e instanceof PaymeException) {
                throw $e;
            }

            throw new PaymeException(
                $this->request_id,
                "Internal error",
                ErrorEnum::INTERNAL_ERROR
            );
        }
    }
}
