<?php

namespace App\Domain\DomainEvent;

/**
 * Event used when the grace period order is confirmed
 */
final readonly class OrderStatusChangedToAwaitingValidationDomainEvent
{
    public function __construct(
        public int $orderId,
        public array $orderItems,
    ) {}
}
