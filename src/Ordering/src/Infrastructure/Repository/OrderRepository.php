<?php

namespace App\Infrastructure\Repository;

use App\Domain\Aggregate\Order\Order;
use App\Domain\Aggregate\Order\OrderRepositoryInterface;
use App\Infrastructure\Mapper\OrderMapper;
use App\Infrastructure\ORM\Entity\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderMapper $orderMapper,
    ) {}

    public function add(Order $order): Order
    {
        $orderEntity = $this->orderMapper->createEntityFromAggregate($order);

        $this->save($orderEntity);

        return $order;
    }

    public function update(Order $order): Order
    {
        /** @var OrderEntity */
        $orderEntity = $this->em->getUnitOfWork()->tryGetById($order->id, OrderEntity::class);

        if ($orderEntity === false) {
            throw new \Exception('There is no entity with this ID in the Doctrine.');
        }

        $this->orderMapper->updateEntityFromAggregate($orderEntity, $order);

        $this->save($orderEntity);

        return $order;
    }

    public function findById(int $id): ?Order
    {
        /** @var OrderEntity */
        $orderEntity = $this->em->getRepository(OrderEntity::class)->findOneById($id);

        if (empty($orderEntity)) {
            return null;
        }

        return $this->orderMapper->createAggregateFromEntity($orderEntity);
    }

    private function save(OrderEntity $orderEntity): void
    {
        foreach ($orderEntity->getOrderItems() as $orderItemEntity) {
            $this->em->persist($orderItemEntity);
        }

        $this->em->persist($orderEntity);
        $this->em->flush();
    }
}
