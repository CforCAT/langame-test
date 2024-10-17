<?php

namespace App\Exceptions;

class CodeThrottleException extends ApplicationException
{
    public function __construct(
        public int $time
    )
    {
        parent::__construct();
    }
}
