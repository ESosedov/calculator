<?php

namespace App\Service\Payment\Interface;

interface PaymentInterface
{
    public function pay(int $price): void;
}