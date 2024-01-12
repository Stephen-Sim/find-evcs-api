<?php

namespace App\Http\Helpers;

class CalculateDistanceHelper{
    public static function calculateDistance($lat1, $long1, $lat2, $long2)
    {
        $d1 = $lat1 * (pi() / 180.0);
        $num1 = $long1 * (pi() / 180.0);
        $d2 = $lat2 * (pi() / 180.0);
        $num2 = $long2 * (pi() / 180.0) - $num1;
        $d3 = pow(sin(($d2 - $d1) / 2.0), 2.0) + cos($d1) * cos($d2) * pow(sin($num2 / 2.0), 2.0);
        return 6371 * (2.0 * atan2(sqrt($d3), sqrt(1.0 - $d3)));
    }
}