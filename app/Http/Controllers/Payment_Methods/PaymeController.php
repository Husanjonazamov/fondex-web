<?php

namespace App\Http\Controllers\Payment_Methods;

use App\Models\PaymentRequest;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Services\PaymeService;
use App\Traits\Processor;
use App\Utils\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JscorpTech\Payme\Models\Order;



class PaymeController extends Controller
{
    use Processor;

    private PaymentRequest $payment;
    private $config;
    private $user;

    public function __construct(PaymentRequest $paymentRequest, User $user)
    {
        $config = $this->payment_config('payme', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config = json_decode($config->test_values);
        }
        $this->payment = $paymentRequest;
        $this->user = $user;
    }


    public function index(Request $request)
    {
        return response()->json($this->response_formatter(GATEWAYS_DEFAULT_200), 200);
    }

    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $payment = $this->payment::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        $data = json_decode($payment->additional_data);
        $address = ShippingAddress::query()->find($data->address_id);
        $delivery_price = carts_delivery_price($address->delivery_method, $data->customer_id, $address->longitude, $address->latitude, $address->district_id);
        $amount = \App\Helpers\PriceHelper::roundToNearestThousand(currencyConverter($payment->payment_amount, "uzs") + $delivery_price) * 100;
        $order = Order::query()->create([
            "user_id" => $payment->user_id ?? $data->customer_id,
            "amount" => $amount,
            "type" => "wallet",
        ]);
        $payment->order_id = $order->id;
        $payment->delivery_price = $delivery_price;
        $payment->save();
        if (!isset($payment)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
        $service = new PaymeService();
        \Log::info('Payme pay request:', [
            'payment_id' => $request['payment_id'] ?? null,
            'amount' => $amount ?? null,
            'order_id' => $order->id ?? null,
            'user_id' => $payment->user_id ?? null,
        ]);

        return redirect($service->generate_link($order));
    }
}
