<?php

namespace App\Form\Product\Trait;

use App\Entity\Product;
use App\Service\Payment\PaymentService;
use App\Validator\Product\CouponCode\CouponCodeConstraint;
use App\Validator\Product\TaxNumber\TaxNumberConstraint;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

trait ProductFormTrait
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('product',EntityType::class,[
                'class' => Product::class,
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('taxNumber', TextType::class, [
                'constraint' => [
                    new NotNull(),
                    new TaxNumberConstraint(),
                ],
            ])
            ->add('couponCode',  TextType::class, [
                'constraint' => [
                    new Length([
                        'min' => 2,
                        ]),
                    new CouponCodeConstraint(),
                ]
            ])
            ->add('paymentProcessor', ChoiceType::class, [
                'choices' => PaymentService::PAYMENT_PROCESSORS,
                'constraint' => [

                ]
            ]);
    }
}
