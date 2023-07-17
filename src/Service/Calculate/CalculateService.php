<?php

namespace App\Service\Calculate;

use App\Entity\Product;
use App\Exception\CalculateException;
use Exception;

class CalculateService
{
    public const COUPON_TYPE_FIX = 'F';
    public const COUPON_TYPE_DISCOUNT ='D';
    public const COUPON_TYPES = [
        self::COUPON_TYPE_FIX,
        self::COUPON_TYPE_DISCOUNT,
    ];
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
    public const TAX_NUMBER_PATTERN = '/^((DE[0-9]{9})|(IT[0-9]{11})|(GR[0-9]{9})|(FR[A-Za-z]{2}[0-9]{9}))$/';

    public function calculateCost(
        Product $product,
        string $taxNumber,
        ?string $couponCode,
    ): int {
        $cost = $product->getCost();
        if (null === $cost) {
            throw new Exception('Product is temporarily unavailable.');
        }

        $discount = 0;
        if (null !== $couponCode) {
            $couponDiscountType = $couponCode[0];
            $discount = match ($couponDiscountType) {
                self::COUPON_TYPE_FIX => (int) substr($couponCode, 1) * 100,
                self::COUPON_TYPE_DISCOUNT => ((int) substr($couponCode, 1)) * $cost / 100,
            };

            if ($discount >= $cost) {
                throw new CalculateException('The discount exceeds the price of product.');
            }
        }

        $taxCountry = substr($taxNumber, 0, 2);
        $taxPercent = self::COUNTRY_TAX_MAP[$taxCountry];
        $costWithDiscount = $cost - $discount;
        $tax = ($costWithDiscount * $taxPercent / 100);
        $costTotal = $costWithDiscount + $tax;

        return $costTotal;
    }
}
