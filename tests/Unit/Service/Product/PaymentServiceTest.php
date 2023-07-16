<?php

namespace App\Tests\Unit\Service\Product;

use App\Entity\Product;
use App\Exception\CalculateException;
use App\Exception\PayException;
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

        $this->paymentService = new PaymentService(
            $paypalPayment,
            $stripePayment,
        );
    }

    /**
     * @dataProvider calculateSuccessProvider
     */
    public function testCalculateSuccess(
        Product $product,
        string $taxNumber,
        ?string $couponCode,
        float $costExpected,
    ): void {

        $costTotalEuro = $this->paymentService->calculateCost(
            $product,
            $taxNumber,
            $couponCode,
        );

        self::assertEquals($costExpected, $costTotalEuro);
    }

    /**
     * @dataProvider calculateErrorProvider
     */
    public function testCalculateError(
        Product $product,
        string $taxNumber,
        ?string $couponCode,
        string $exceptionMessage,
    ): void {
        $this->expectException(CalculateException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->paymentService->calculateCost(
            $product,
            $taxNumber,
            $couponCode,
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

    public function calculateSuccessProvider(): array
    {
        $product = new Product();
        $product->setCost(10000);

        return [
            [
                $product,
                'DE123456789',
                'D15',
                10115,
            ],
            [
                $product,
                'IT12345678901',
                'F50',
                6100,
            ],
            [
                $product,
                'FRFJ1234567',
                null,
                12000,
            ],
            [
                $product,
                'GR123456789',
                'D50',
                6200
            ]
        ];
    }

    public function calculateErrorProvider(): array
    {
        $product = new Product();
        $product->setCost(100);

        return [
            [
                $product,
                'DE123456789',
                'F10',
                'Размер скидки превышает стоимость товара',
            ],
        ];
    }
}
