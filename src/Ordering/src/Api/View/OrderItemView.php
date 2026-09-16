<?php

namespace App\Api\View;

use OpenApi\Attributes as OA;

final readonly class OrderItemView
{
    public function __construct(
        public string $productName,
        public int $units,
        #[OA\Property(default: '25.49')]
        public string $unitPrice,
        public string $pictureUrl,
    ) {}
}
