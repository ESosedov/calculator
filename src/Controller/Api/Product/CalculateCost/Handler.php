<?php

namespace App\Controller\Api\Product\CalculateCost;

use App\Controller\Api\Product\CalculateCost\DTO\ProductCalculateCostDTO;
use App\Model\Product\Factory\ProductCostModelFactory;
use App\Model\Product\ProductCostModel;
use App\Service\Calculate\CalculateService;

class Handler
{
    public function __construct(
        private CalculateService $calculateService,
        private ProductCostModelFactory $productModelFactory,
    ) {
    }

    public function handle(ProductCalculateCostDTO $dto): ProductCostModel
    {
        $product = $dto->getProduct();

        $cost = $this->calculateService->calculateCost(
            $product,
            $dto->getTaxNumber(),
            $dto->getCouponCode(),
        );

        return $this->productModelFactory->fromProduct($product, $cost);
    }
}
