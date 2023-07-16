<?php

namespace App\Service\Payment;

use App\Entity\Product;
use App\Exception\CalculateException;
use App\Service\Payment\Interface\PaymentInterface;
use Exception;

class PaymentService
{
    public const COUPON_TYPE_FIX = 'F';
    public const COUPON_TYPE_DISCOUNT ='D';
    public const TAX_PERCENT_GERMANY = 19;
    public const TAX_PERCENT_ITALY = 22;
    public const TAX_PERCENT_FRANCE = 20;
    public const TAX_PERCENT_GREECE = 24;

    public const COUNTRY_TAX_MAP = [
        'DE' => self::TAX_PERCENT_GERMANY,
        'IT' => self::TAX_PERCENT_ITALY,
        'GR' => self::TAX_PERCENT_GREECE,
        'FR' => self::TAX_PERCENT_FRANCE,
    ];
    public function __construct(private PaymentInterface $payment)
    {
    }

    public function pay(): void
    {
        $cost = $this->calculateCost();
        $this->payment->pay($cost);
    }
    public function calculateCost(
        Product $product,
        string $taxNumber,
        ?string $couponCode,
    ): float
    {
        $cost = $product->getCost();
        if (null === $cost) {
            throw new Exception('В настоящий момент невозможно рассчитать стоимость товара');
        }

        $discount = 0;
        if (null !== $couponCode) {
            $couponDiscountType = $couponCode[0];
            $discount = match ($couponDiscountType) {
                self::COUPON_TYPE_FIX => (int) substr($couponCode, 1) * 100,
                self::COUPON_TYPE_DISCOUNT => ((int) substr($couponCode, 1)) * $cost / 100,
            };

            if ($discount >= $cost) {
                throw new CalculateException('Размер скидки превышает стоимость товара');
            }
        }

        $taxCountry = substr($taxNumber, 0, 2);
        $taxPercent = self::COUNTRY_TAX_MAP[$taxCountry];
        $costWithDiscount = $cost - $discount;
        $tax = ($costWithDiscount * $taxPercent / 100);
        $costTotalEuro = round(($costWithDiscount + $tax)  / 100, 2);

        return $costTotalEuro;
    }
}
