<?php

namespace App\Domain\DomainEvent;

use App\Domain\Aggregate\Order\Order;

final readonly class OrderShippedDomainEvent
{
    public function __construct(
        public Order $order,
    ) {}
}
