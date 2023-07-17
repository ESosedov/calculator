<?php

namespace App\Form\Product;

use App\Controller\Api\Product\Pay\DTO\ProductPayDTO;
use App\Form\ApiForm;
use App\Service\Payment\PaymentService;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class PayProductForm extends ProductBaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('paymentProcessor', ChoiceType::class, [
                'choices' => PaymentService::PAYMENT_PROCESSORS,
                'constraints' => [
                    new NotNull(),
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => ProductPayDTO::class,
        ]);
    }
}
