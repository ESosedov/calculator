<?php

namespace App\Tests\Unit\Service;

use App\Entity\Product;
use App\Exception\CalculateException;
use App\Service\Payment\Interface\PaymentInterface;
use App\Service\Payment\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();

        $payment = $this->createMock(PaymentInterface::class);
        $this->paymentService = new PaymentService($payment);
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

    public function calculateSuccessProvider(): array
    {
        $product = new Product();
        $product->setCost(10000);

        return [
            [
                $product,
                'DE123456789',
                'D15',
                101.15,
            ],
            [
                $product,
                'IT12345678901',
                'F50',
                61.00,
            ],
            [
                $product,
                'FRFJ1234567',
                null,
                120.00,
            ],
            [
                $product,
                'GR123456789',
                'D50',
                62.00
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
