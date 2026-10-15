<?php

namespace App\Domain\DomainEvent;

/**
 * Event used when the order stock items are confirmed
 */
final readonly class OrderStatusChangedToStockConfirmedDomainEvent
{
    public function __construct(
        public int $orderId,
    ) {}
}
