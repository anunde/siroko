<?php

namespace App\Cart\Domain\Exception;

use Exception;

class CartInProcessPaymentException extends Exception
{
    public function __construct($message = "Cart is in process of payment and cannot be modified", $code = 400, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
