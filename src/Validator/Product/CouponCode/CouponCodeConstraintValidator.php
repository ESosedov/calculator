<?php

namespace App\Validator\Product\CouponCode;

use App\Service\Calculate\CalculateService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CouponCodeConstraintValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$constraint instanceof CouponCodeConstraint) {
            throw new UnexpectedTypeException($constraint, CouponCodeConstraint::class);
        }

        $couponDiscountType = $value[0];
        if (!in_array($couponDiscountType, CalculateService::COUPON_TYPES, true)) {
            $this->context->buildViolation($constraint->errorMessage)->addViolation();

            return;
        }

        $couponValue = substr($value, 1);
        if (false === is_numeric($couponValue)) {
            $this->context->buildViolation($constraint->errorMessage)->addViolation();

            return;
        }

        if (CalculateService::COUPON_TYPE_DISCOUNT === $couponDiscountType
            && 99 < (int) $couponValue) {
            $this->context->buildViolation($constraint->errorMessage)->addViolation();
        }
    }
}