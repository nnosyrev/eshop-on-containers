<?php

namespace App\Api\View;

use DateTime;
use OpenApi\Attributes as OA;

final readonly class OrderSummaryView
{
    public function __construct(
        public int $orderNumber,
        #[OA\Property(default: '2026-09-15T19:20:10+00:00')]
        public DateTime $date,
        public int $status,
        #[OA\Property(default: '2339.22')]
        public string $total,
    ) {}
}
