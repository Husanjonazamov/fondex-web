<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentSucceeded
{
    use Dispatchable, SerializesModels;

    public $order;
    public $transaction;
    public $user;

    public function __construct($order, $transaction, $user = null)
    {
        $this->order = $order;
        $this->transaction = $transaction;
        $this->user = $user;
    }
}
