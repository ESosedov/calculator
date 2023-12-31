<?php

namespace App\Validator\Product\TaxNumber;

use App\Service\Calculate\CalculateService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TaxNumberConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TaxNumberConstraint) {
            throw new UnexpectedTypeException($constraint, TaxNumberConstraint::class);
        }

        if (!preg_match(CalculateService::TAX_NUMBER_PATTERN, $value)) {
            $this->context->buildViolation($constraint->errorMessage)->addViolation();
        }
    }
}
