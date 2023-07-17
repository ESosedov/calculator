<?php

namespace App\Controller\Api\Product\Pay\DTO;

use App\Controller\Api\Product\DTO\ProductDTOTrait;

class ProductPayDTO
{
    use ProductDTOTrait;

    private ?string $paymentProcessor;

    public function getPaymentProcessor(): ?string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(?string $paymentProcessor): self
    {
        $this->paymentProcessor = $paymentProcessor;

        return $this;
    }
}
