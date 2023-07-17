<?php

namespace App\Tests\Unit\Validator\TaxNumber;

use App\Validator\Product\TaxNumber\TaxNumberConstraint;
use App\Validator\Product\TaxNumber\TaxNumberConstraintValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class TaxNumberConstraintValidatorTest extends TestCase
{
    private TaxNumberConstraintValidator $validator;
    private ExecutionContextInterface|MockObject $executionContextMock;
    private ConstraintViolationBuilderInterface|MockObject $constraintViolationBuilderMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $this->constraintViolationBuilderMock = $this->createMock(ConstraintViolationBuilderInterface::class);
        $this->validator = new TaxNumberConstraintValidator(
            $this->executionContextMock,
            $this->constraintViolationBuilderMock,
        );

        $this->validator->initialize($this->executionContextMock);
    }

    public function testTaxNumberSuccessValidation(): void
    {
        $this->executionContextMock
            ->expects(self::never())
            ->method('buildViolation');

        $this->validator->validate('FRAN123456789', new TaxNumberConstraint());
    }

    public function testTaxNumberErrorValidation(): void
    {
        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Invalid tax number.')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->validator->validate('DE1234567890', new TaxNumberConstraint());
    }
}
