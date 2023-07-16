<?php

namespace App\Service\Payment;

use App\Service\Payment\Interface\PaymentInterface;
use App\Service\Payment\PaymentProcessor\StripePaymentProcessor;
use Exception;

class StripePayment implements PaymentInterface
{
    public function __construct(private StripePaymentProcessor $stripePaymentProcessor)
    {
    }

    /**
     * @throws Exception
     */
    public function pay(int $price): void
    {
        $this->stripePaymentProcessor->processPayment($price);
    }
}