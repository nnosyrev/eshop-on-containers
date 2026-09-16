<?php

namespace App\Api\View;

use DateTime;
use OpenApi\Attributes as OA;

final readonly class OrderView
{
    public function __construct(
        public int $orderNumber,
        #[OA\Property(default: '2026-09-15T19:20:10+00:00')]
        public DateTime $date,
        public int $status,
        public string $description,
        public string $street,
        public string $city,
        public string $state,
        public string $zipcode,
        public string $country,
        /**
         * @var OrderItemView[]
         */
        public array $orderItems,
        #[OA\Property(default: '2339.22')]
        public string $total,
    ) {}
}
