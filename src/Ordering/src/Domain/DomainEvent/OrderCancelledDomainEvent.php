<?php

namespace App\Domain\DomainEvent;

use App\Domain\Aggregate\Order\Order;

final readonly class OrderCancelledDomainEvent
{
    public function __construct(
        public Order $order,
    ) {}
}
