<?php

namespace App\Validator\Product\TaxNumber;

use Symfony\Component\Validator\Constraint;

class TaxNumberConstraint extends Constraint
{
    public string $errorMessage = 'Некорректный формат налогового номера';
}
