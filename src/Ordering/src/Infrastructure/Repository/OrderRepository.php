<?php

namespace App\Infrastructure\Repository;

use App\Domain\Aggregate\Order\Order;
use App\Domain\Aggregate\Order\OrderRepositoryInterface;
use App\Infrastructure\ORM\Entity\BuyerEntity;
use App\Infrastructure\ORM\Entity\OrderEntity;
use App\Infrastructure\ORM\Entity\PaymentMethodEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function add(Order $order): Order
    {
        $orderEntity = OrderEntity::createFromAggregate(
            $order,
            $order->buyerId ? $this->em->getReference(BuyerEntity::class, $order->buyerId) : null,
            $order->paymentMethodId ? $this->em->getReference(PaymentMethodEntity::class, $order->paymentMethodId) : null,
        );

        foreach ($orderEntity->getOrderItems() as $orderItemEntity) {
            $this->em->persist($orderItemEntity);
        }

        $this->em->persist($orderEntity);
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
