<?php

namespace App\Service\Payment\PaymentProcessor;

use Exception;

class StripePaymentProcessor
{
    /**
     * @throws Exception in case of a failed payment
     */
    public function processPayment(int $price): void
    {
        if ($price < 10) {
            throw new Exception('Too low price');
        }

        //process payment logic
    }
}