<?php

namespace App\Tests\Unit\Service\Product;

use App\Entity\Product;
use App\Exception\CalculateException;
use App\Service\Calculate\CalculateService;
use PHPUnit\Framework\TestCase;

class CalculateServiceTest extends TestCase
{
    private CalculateService $calculateService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculateService = new CalculateService();
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

        $costTotalEuro = $this->calculateService->calculateCost(
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

        $this->calculateService->calculateCost(
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
                'The discount exceeds the price of product.',
            ],
        ];
    }
}
