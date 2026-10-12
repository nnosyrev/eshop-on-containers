<?php

namespace App\Api\Controller;

use App\Api\Query\OrderQueriesInterface;
use App\Api\View\CardTypeView;
use App\Api\View\OrderSummaryView;
use App\Api\View\OrderView;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[OA\Tag(name: 'API')]
#[Route('/api/v1/orders')]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderQueriesInterface $orderQueries,
        private readonly DenormalizerInterface $denormalizer,
    ) {}

    #[Route('/{id}', methods: ['GET'], requirements: ['id' => '\d+'], name: 'app_order')]
    #[OA\Response(
        response: 200,
        description: 'Returns the order',
        content: new Model(type: OrderView::class)
    )]
    public function getOrder(int $id): JsonResponse
    {
        $orderData = $this->orderQueries->getOrder($id);

        $order = $this->denormalizer->denormalize($orderData, OrderView::class);

        return $this->json($order);
    }

    #[Route('/', methods: ['GET'], requirements: ['id' => '\d+'], name: 'app_orders_by_user')]
    #[OA\Response(
        response: 200,
        description: 'Returns the orders summary',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: OrderSummaryView::class))
        )
    )]
    public function getOrdersByUser(): JsonResponse
    {
        // TODO:
        $userId = 'guid';

        $orderSummariesData = $this->orderQueries->getOrdersFromUser($userId);

        $orderSummaries = $this->denormalizer->denormalize($orderSummariesData, OrderSummaryView::class . '[]');

        return $this->json($orderSummaries);
    }

    #[Route('/card-types', methods: ['GET'], name: 'app_card_types')]
    #[OA\Response(
        response: 200,
        description: 'Returns the card types',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CardTypeView::class))
        )
    )]
    public function getCardTypes(): JsonResponse
    {
        $cardTypesData = $this->orderQueries->getCardTypes();

        $cardTypes = $this->denormalizer->denormalize($cardTypesData, CardTypeView::class . '[]');

        return $this->json($cardTypes);
    }
}
