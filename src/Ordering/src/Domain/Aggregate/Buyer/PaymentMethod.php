<?php

namespace App\Domain\Aggregate\Buyer;

use DateTime;
use Exception;

class PaymentMethod
{
    public function __construct(
        private int $cardTypeId,
        private string $alias,
        private string $cardNumber,
        private string $securityNumber,
        private string $cardHolderName,
        private DateTime $expiration,
    ) {
        if ($expiration->getTimestamp() < new DateTime()->getTimestamp()) {
            throw new Exception('Error.');
        }
    }

    public function isEqualTo(int $cardTypeId, string $cardNumber, DateTime $expiration): bool
    {
        return $this->cardTypeId === $cardTypeId
            && $this->cardNumber === $cardNumber
            && $this->expiration === $expiration;
    }
}
