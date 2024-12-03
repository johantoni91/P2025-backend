<?php

namespace App\Helper;

class Face
{
    public static function calculateDistance($descriptor1, $descriptor2)
    {
        $sum = 0.0;
        for ($i = 0; $i < count($descriptor1); $i++) {
            $sum += pow((int)$descriptor1[$i] - (int)$descriptor2[$i], 2);
        }
        return sqrt($sum);
    }
}
