<?php

use Illuminate\Support\Env;
use JscorpTech\Payme\Models\Order;
use JscorpTech\Payme\Models\Transaction;
use App\Handlers\PaymeHandler;

return [
    "order_table" => Env::get("PAYME_ORDER_TABLE", "payme_orders"),
    "login" => Env::get("PAYME_LOGIN", "Paycom"),
    "key" => Env::get("PAYME_KEY", "1234567890"),
    "merchant_id" => Env::get("PAYME_ID", "1234567890"),
    "amount" => Env::get("PAYME_AMOUNT", "amount"),
    "field" => Env::get("PAYME_ACCOUNT_FIELD", "order_id"),
    "order" => Order::class,
    "transaction" => Transaction::class,
    "one_time_payment" => Env::get("PAYME_ONE_TIME_PAYMENT", true),
    "success_callback" => [PaymeHandler::class, "success"],
    "cancel_callback" => [PaymeHandler::class, "cancel"],
];
