<?php

namespace App\Tests\Unit\Service\Product;

use App\Entity\Product;
use App\Exception\PayException;
use App\Service\Calculate\CalculateService;
use App\Service\Payment\PaymentProcessor\PaypalPaymentProcessor;
use App\Service\Payment\PaymentProcessor\StripePaymentProcessor;
use App\Service\Payment\PaymentService;
use App\Service\Payment\PaypalPayment;
use App\Service\Payment\StripePayment;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();

        $paypalPaymentProcessor = new PaypalPaymentProcessor();
        $paypalPayment = new PaypalPayment($paypalPaymentProcessor);

        $stripePaymentProcessor = new StripePaymentProcessor();
        $stripePayment = new StripePayment($stripePaymentProcessor);

        $calculateService = new CalculateService();

        $this->paymentService = new PaymentService(
            $paypalPayment,
            $stripePayment,
            $calculateService,
        );
    }

    public function testPay(): void {
        $product = new Product();
        $product->setCost(10000);

        $this->expectException(PayException::class);
        $this->expectExceptionMessage('Too high price');

        $this->paymentService->pay(
            $product,
            'GR123456789',
            'paypal',
            null,
        );
    }
}
