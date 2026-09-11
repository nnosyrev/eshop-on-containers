<?php

namespace App\Api\Controller;

use App\Api\Query\OrderQueriesInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_order_list')]
    public function list(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrderController.php',
        ]);
    }

    #[Route('/card-types', name: 'app_card_types')]
    public function getCardTypes(OrderQueriesInterface $orderQueries): JsonResponse
    {
        $cardTypes = $orderQueries->getCardTypes();

        return $this->json($cardTypes);
    }
}
