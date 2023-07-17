<?php

namespace App\Model\Product\Factory;

use App\Entity\Product;
use App\Model\Product\ProductCostModel;

class ProductCostModelFactory
{
    public function fromProduct(
        Product $product,
        int $cost,
    ): ProductCostModel {
        return new ProductCostModel(
            $product->getId(),
            round($cost / 100 , 2),
        );
    }
}