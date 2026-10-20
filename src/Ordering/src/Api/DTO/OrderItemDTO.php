<?php

namespace App\Api\DTO;

use OpenApi\Attributes as OA;

final readonly class OrderItemDTO
{
    public function __construct(
        public int $productId,
        public string $productName,
        #[OA\Property(default: '25.49')]
        public string $unitPrice,
        #[OA\Property(default: '5.00')]
        public string $discount,
        #[OA\Property(default: 1)]
        public int $units,
        public string $pictureUrl,
    ) {}
}
