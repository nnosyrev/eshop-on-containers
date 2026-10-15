<?php

namespace App\Domain\DomainEvent;

use App\Domain\Aggregate\Buyer\Buyer;
use App\Domain\Aggregate\Buyer\PaymentMethod;

/**
 * Event used when the grace period order is confirmed
 */
final readonly class BuyerAndPaymentMethodVerifiedDomainEvent
{
    public function __construct(
        public Buyer $buyer,
        public PaymentMethod $payment,
        public int $orderId,
    ) {}
}
