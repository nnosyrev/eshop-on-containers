<?php

namespace App\Api\Command;

final readonly class ShipOrderCommand
{
    public function __construct(
        public int $orderId,
    ) {}
}
