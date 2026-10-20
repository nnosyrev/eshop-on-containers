<?php

namespace App\Infrastructure\Repository;

use App\Domain\Aggregate\Order\Order;
use App\Domain\Aggregate\Order\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function add(Order $order): Order
    {
        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }

    public function update(Order $order): Order
    {

        return $order;
    }

    public function findById(int $id): Order
    {
        //return new Order()
    }
}
