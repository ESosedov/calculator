<?php

namespace App\Service\Payment;

use App\Entity\Product;
use App\Exception\CalculateException;
use App\Exception\PayException;
use App\Service\Calculate\CalculateService;
use App\Service\Payment\Interface\PaymentInterface;
use Exception;

class PaymentService
{
    public const PAYMENT_PROCESSOR_PAYPAL = 'paypal';
    public const PAYMENT_PROCESSOR_STRIPE = 'stripe';
    public const PAYMENT_PROCESSORS = [
        self::PAYMENT_PROCESSOR_PAYPAL,
        self::PAYMENT_PROCESSOR_STRIPE,
    ];

    public function __construct(
        private PaypalPayment $paypalPayment,
        private StripePayment $stripePayment,
        private CalculateService $calculateService,
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
        $cost = $this->calculateService->calculateCost(
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

    private function paymentMethodChoices(string $paymentProcessor): PaymentInterface
    {
        return  match ($paymentProcessor) {
            self::PAYMENT_PROCESSOR_PAYPAL => $this->paypalPayment,
            self::PAYMENT_PROCESSOR_STRIPE => $this->stripePayment,
        };
    }
}
