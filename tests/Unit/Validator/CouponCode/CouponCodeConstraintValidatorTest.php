<?php

namespace App\Tests\Unit\Validator\CouponCode;

use App\Validator\Product\CouponCode\CouponCodeConstraint;
use App\Validator\Product\CouponCode\CouponCodeConstraintValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class CouponCodeConstraintValidatorTest extends TestCase
{
    private CouponCodeConstraintValidator $validator;
    private ExecutionContextInterface|MockObject $executionContextMock;
    private ConstraintViolationBuilderInterface|MockObject $constraintViolationBuilderMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $this->constraintViolationBuilderMock = $this->createMock(ConstraintViolationBuilderInterface::class);
        $this->validator = new CouponCodeConstraintValidator(
            $this->executionContextMock,
            $this->constraintViolationBuilderMock,
        );

        $this->validator->initialize($this->executionContextMock);
    }

    public function testSuccessValidation():void
    {
        $this->executionContextMock
            ->expects(self::never())
            ->method('buildViolation');

        $this->validator->validate('F10', new CouponCodeConstraint());
    }

    public function testCouponTypeError(): void
    {
        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Некорректный код купона')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->validator->validate('E10', new CouponCodeConstraint());
    }

    public function testCouponValueError(): void
    {
        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Некорректный код купона')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->validator->validate('F4l', new CouponCodeConstraint());
    }

    public function testCouponPercentError(): void
    {
        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Некорректный код купона')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->validator->validate('D200', new CouponCodeConstraint());
    }
}
