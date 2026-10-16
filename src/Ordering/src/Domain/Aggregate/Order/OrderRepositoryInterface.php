<?php

namespace App\Domain\Aggregate\Order;

interface OrderRepositoryInterface
{
    public function add(Order $order): Order;

    public function update(Order $order): Order;

    public function findById(int $id): Order;
}
