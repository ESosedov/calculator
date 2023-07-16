<?php

namespace App\Validator\Product\CouponCode;

use Symfony\Component\Validator\Constraint;

class CouponCodeConstraint extends Constraint
{
    public string $errorMessage = 'Некорректный код купона';
}
