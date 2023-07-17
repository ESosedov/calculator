<?php

namespace App\Form\Product;

use App\Controller\Api\Product\CalculateCost\DTO\ProductCalculateCostDTO;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalculateCostForm extends ProductBaseForm
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => ProductCalculateCostDTO::class,
        ]);
    }
}
