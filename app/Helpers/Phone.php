<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Phone
{
    public static function normalize(string $phone): string
    {
        return Str::replaceStart('8', '7', preg_replace('/[^0-9]/', '', $phone));
    }
}
