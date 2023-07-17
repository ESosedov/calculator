<?php

namespace App\Controller\Api\Product\Pay;

use App\Controller\Api\ApiController;
use App\Controller\Api\Product\Pay\DTO\ProductPayDTO;
use App\Form\Product\PayForm;
use App\Service\Payment\PaymentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class PayAction extends ApiController
{
    #[Route('/api/product/pay', ['POST'])]
    public function payProduct(Request $request, PaymentService $paymentService): JsonResponse
    {
        $dto = new ProductPayDTO();
        $form = $this->createForm(PayForm::class, $dto);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isValid()) {
            try {
                $paymentService->pay(
                    $dto->getProduct(),
                    $dto->getTaxNumber(),
                    $dto->getPaymentProcessor(),
                    $dto->getCouponCode(),
                );

                return $this->emptyResponse();
            } catch (Throwable $exception) {
                return $this->apiErrorResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json($this->gatherFormErrors($form), Response::HTTP_BAD_REQUEST);
    }
}
