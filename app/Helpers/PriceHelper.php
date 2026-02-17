<?php

namespace App\Helpers;

class PriceHelper
{
    /**
     * Round the amount to the nearest thousand.
     * If digits >= 500, round up. Otherwise, round down.
     * 
     * @param mixed $amount
     * @return float
     */
    public static function roundToNearestThousand($amount)
    {
        $num = (float) $amount;
        $remainder = $num % 1000;
        if ($remainder >= 500) {
            return ceil($num / 1000) * 1000;
        } else {
            return floor($num / 1000) * 1000;
        }
    }
}
