<?php

namespace App\Domain\DomainEvent;

use App\Domain\Aggregate\Order\Order;
use DateTime;

/**
 * Event used when an order is created
 */
final readonly class OrderStartedDomainEvent
{
    public function __construct(
        public Order $order,
        public string $userId,
        public string $userName,
        public int $cardTypeId,
        public string $cardNumber,
        public string $cardSecurityNumber,
        public string $cardHolderName,
        public DateTime $cardExpiration,
    ) {}
}
