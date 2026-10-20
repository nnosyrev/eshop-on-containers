<?php

namespace App\Api\Command;

use DateTimeImmutable;

final readonly class CreateOrderCommand
{
    public function __construct(
        /**
         * @var \App\Api\DTO\OrderItemDTO[]
         */
        public array $orderItems,
        public string $userId,
        public string $userName,
        public string $city,
        public string $street,
        public string $state,
        public string $country,
        public string $zipcode,
        public string $cardNumber,
        public string $cardHolderName,
        public DateTimeImmutable $cardExpiration,
        public string $cardSecurityNumber,
        public int $cardTypeId
    ) {}
}
