<?php

namespace App\Controller\Api\Product\CalculateCost;

use App\Controller\Api\ApiController;
use App\Controller\Api\Product\CalculateCost\DTO\ProductCalculateCostDTO;
use App\Form\Product\CalculateProductForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class CalculateCostAction extends ApiController
{
    #[Route('/api/product/calculate-cost', ['POST'])]
    public function calculateCost(Request $request, Handler $handler): JsonResponse
    {
        $dto = new ProductCalculateCostDTO();
        $form = $this->createForm(CalculateProductForm::class, $dto);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isValid()) {
            try {
                $model = $handler->handle($dto);

                return $this->json($model);
            } catch (Throwable $exception) {
                dump($exception);
                return $this->apiErrorResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json($this->gatherFormErrors($form), Response::HTTP_BAD_REQUEST);
    }
}
