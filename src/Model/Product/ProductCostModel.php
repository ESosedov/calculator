<?php

namespace App\Model\Product;

class ProductCostModel
{
    public function __construct(
        private int $id,
        private float $price,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
