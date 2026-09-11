<?php

namespace App\Api\View;

final readonly class OrderItemView
{
    public function __construct(
        public string $productName,
        public int $units,
        public float $unitPrice, // TODO: ???
        public string $pictureUrl,
    ) {}
}
