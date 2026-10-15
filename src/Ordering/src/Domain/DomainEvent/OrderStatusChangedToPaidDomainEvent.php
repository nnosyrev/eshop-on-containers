<?php

namespace App\Domain\DomainEvent;

/**
 * Event used when the order is paid
 */
final readonly class OrderStatusChangedToPaidDomainEvent
{
    public function __construct(
        public int $orderId,
        public array $orderItems,
    ) {}
}
