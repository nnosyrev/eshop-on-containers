<?php

namespace App\Domain\Aggregate\Order;

final readonly class Address
{
    public function __construct(
        public string $street,
        public string $city,
        public string $state,
        public string $country,
        public string $zipCode,
    ) {}
}
