<?php

namespace App\Form\Product;

use App\Entity\Product;
use App\Form\ApiForm;
use App\Validator\Product\CouponCode\CouponCodeConstraint;
use App\Validator\Product\TaxNumber\TaxNumberConstraint;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

Class ProductBaseForm extends ApiForm
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('taxNumber', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new TaxNumberConstraint(),
                ],
            ])
            ->add('couponCode', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                    ]),
                    new CouponCodeConstraint(),
                ],
            ]);
    }
}
