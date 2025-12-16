<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Env;

class PaymeService
{
    public $merchant_id;
    public $base_url;

    public function __construct()
    {
        $this->merchant_id = Env::get("PAYME_ID");
        $this->base_url = Env::get("PAYME_URL");
        if ($this->merchant_id == null or $this->base_url == null) {
            throw new Exception("payme uchun kerakli malumotlar topilmadi");
        }
    }

    public function generate_link($order)
    {
        $amount = $order->amount;
        $url = "https://emart.felix-its.uz";
        $payload = base64_encode("m=$this->merchant_id;ac.order_id=$order->id;a=$amount;c=$url");
        return $this->base_url . "/" . $payload;
    }
}
