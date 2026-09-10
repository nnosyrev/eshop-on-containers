<?php

namespace App\Domain\Aggregate\Buyer;

use App\Domain\Aggregate\AbstractAggregate;
use App\Domain\Aggregate\AggregateRootInterface;
use DateTime;

class Buyer extends AbstractAggregate implements AggregateRootInterface
{
    private array $paymentMethods = [];

    public function __construct(
        public readonly string $identityGuid,
        public readonly string $name
    ) {}

    public function verifyOrAddPaymentMethod(
        int $cardTypeId,
        string $alias,
        string $cardNumber,
        string $securityNumber,
        string $cardHolderName,
        DateTime $expiration,
        int $orderId
    ): PaymentMethod {
        $payment = new PaymentMethod(
            $cardTypeId,
            $alias,
            $cardNumber,
            $securityNumber,
            $cardHolderName,
            $expiration
        );

        $this->paymentMethods[] = $payment;

        return $payment;
    }
}
