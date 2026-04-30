<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaymentSucceeded
{
    use Dispatchable, SerializesModels;

    public $order;
    public $transaction;

    public function __construct($order, $transaction)
    {
        $this->order       = $order;
        $this->transaction = $transaction;
    }
}
