<?php

namespace App\Service\Payment;

use App\Entity\Product;
use App\Exception\CalculateException;
use App\Exception\PayException;
use App\Service\Payment\Interface\PaymentInterface;
use Exception;

class PaymentService
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

    public const PAYMENT_PROCESSOR_PAYPAL = 'paypal';
    public const PAYMENT_PROCESSOR_STRIPE = 'stripe';
    public const PAYMENT_PROCESSORS = [
        self::PAYMENT_PROCESSOR_PAYPAL,
        self::PAYMENT_PROCESSOR_STRIPE,
    ];
    public const TAX_NUMBER_PATTERN = '/^((DE[0-9]{9})|(IT[0-9]{11})|(GR[0-9]{9})|(FR[A-Za-z]{2}[0-9]{9}))$/';

    public function __construct(
        private PaypalPayment $paypalPayment,
        private StripePayment $stripePayment,
    ) {
    }

    /**
     * @throws PayException
     * @throws CalculateException
     */
    public function pay(
        Product $product,
        string $taxNumber,
        string $paymentProcessor,
        ?string $couponCode,
    ): void {
        $cost = $this->calculateCost(
            $product,
            $taxNumber,
            $couponCode,
        );

        $paymentMethod = $this->paymentMethodChoices($paymentProcessor);

        try {
            $paymentMethod->pay($cost);
        } catch (Exception $exception) {
            throw new PayException($exception->getMessage());
        }
    }
    public function calculateCost(
        Product $product,
        string $taxNumber,
        ?string $couponCode,
    ): int {
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
        $costTotal = $costWithDiscount + $tax;

        return $costTotal;
    }

    private function paymentMethodChoices(string $paymentProcessor): PaymentInterface
    {
        return  match ($paymentProcessor) {
            self::PAYMENT_PROCESSOR_PAYPAL => $this->paypalPayment,
            self::PAYMENT_PROCESSOR_STRIPE => $this->stripePayment,
        };
    }
}
