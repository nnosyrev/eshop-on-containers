<?php

namespace App\Api\Controller;

use App\Api\Query\OrderQueriesInterface;
use App\Api\View\CardTypeView;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Route('/api/v1')]
#[OA\Tag(name: 'API')]
final class OrderController extends AbstractController
{
    #[Route('/card-types', methods: ['GET'], name: 'app_card_types')]
    #[OA\Response(
        response: 200,
        description: 'Returns the card types',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: CardTypeView::class))
        )
    )]
    public function getCardTypes(OrderQueriesInterface $orderQueries, DenormalizerInterface $denormalizer): JsonResponse
    {
        $cardTypesData = $orderQueries->getCardTypes();

        $cardTypes = $denormalizer->denormalize($cardTypesData, CardTypeView::class . '[]');

        return $this->json($cardTypes);
    }
}
