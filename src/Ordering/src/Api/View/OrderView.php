<?php

namespace App\Api\View;

use DateTime;

final readonly class OrderView
{
    public function __construct(
        public int $orderNumber,
        public DateTime $date,
        public string $status,
        public string $description,
        public string $street,
        public string $city,
        public string $state,
        public string $zipcode,
        public string $country,
        public array $orderItems,
        public float $total, // TODO: ???
    ) {}
}
