<?php

namespace App\Services\Auth;

class CodeGenerator
{
    public function generate(int $length): string
    {
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= rand(0, 9);
        }

        return $code;
    }
}
