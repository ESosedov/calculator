<?php

namespace App\Service\Payment;

use App\Service\Payment\Interface\PaymentInterface;
use App\Service\Payment\PaymentProcessor\PaypalPaymentProcessor;
use Exception;

class PaypalPayment implements PaymentInterface
{
    public function __construct(private PaypalPaymentProcessor $paypalPaymentProcessor)
    {
    }

    /**
     * @throws Exception
     */
    public function pay(int $price): void
    {
        $this->paypalPaymentProcessor->pay($price);
    }
}