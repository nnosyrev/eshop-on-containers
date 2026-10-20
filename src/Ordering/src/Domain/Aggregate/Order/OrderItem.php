<?php

namespace App\Domain\Aggregate\Order;

use App\Domain\Aggregate\AbstractAggregate;
use Exception;

class OrderItem extends AbstractAggregate
{
    private Order $order;

    public function __construct(
        Order $order,
        public readonly int $productId,
        public readonly string $productName,
        // TODO: have to be decimal
        public readonly float $unitPrice,
        public float $discount,
        public readonly string $pictureUrl,
        public readonly int $units = 1
    ) {
        if ($units <= 0) {
            throw new Exception('Invalid number of units');
        }

        if (($unitPrice * $units) < $discount) {
            throw new Exception('The total of order item is lower than applied discount');
        }

        $this->order = $order;
    }

    public function setDiscount(float $discount): void
    {
        if ($discount < 0) {
            throw new Exception('Discount is not valid');
        }

        $this->discount = $discount;
    }

    public function addUnits(int $units): void
    {
        if ($units < 0) {
            throw new Exception('Invalid units');
        }

        $this->units += $units;
    }
}
