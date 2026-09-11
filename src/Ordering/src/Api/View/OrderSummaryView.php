<?php

namespace App\Api\View;

use DateTime;

final readonly class OrderSummaryView
{
    public function __construct(
        public int $orderNumber,
        public DateTime $date,
        public string $status,
        public float $total, // TODO: ???
    ) {}
}
