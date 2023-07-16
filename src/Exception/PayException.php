<?php

namespace App\Exception;

use Exception;

class PayException extends Exception
{
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
