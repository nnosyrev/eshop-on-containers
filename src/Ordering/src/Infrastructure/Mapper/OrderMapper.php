<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Aggregate\Order\Order;
use App\Infrastructure\ORM\Entity\BuyerEntity;
use App\Infrastructure\ORM\Entity\OrderEntity;
use App\Infrastructure\ORM\Entity\PaymentMethodEntity;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

final readonly class OrderMapper
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderItemMapper $orderItemMapper,
        private AddressMapper $addressMapper,
    ) {}

    public function createEntityFromAggregate(Order $order): OrderEntity
    {
        $orderEntity = new OrderEntity()
            ->setId($order->id);

        $this->doUpdateEntityFromAggregate($orderEntity, $order);

        foreach ($order->orderItems as $orderItem) {
            $orderItemEntity = $this->orderItemMapper->createEntityFromAggregate($orderItem, $orderEntity);

            $orderEntity->addOrderItem($orderItemEntity);
        }

        return $orderEntity;
    }

    public function updateEntityFromAggregate(OrderEntity $orderEntity, Order $order): void
    {
        $this->doUpdateEntityFromAggregate($orderEntity, $order);

        foreach ($order->orderItems as $orderItem) {
            if (!empty($orderItem->id)) {
                foreach ($orderEntity->getOrderItems() as $orderItemEntity) {
                    if ($orderItem->id === $orderItemEntity->getId()) {
                        $this->orderItemMapper->updateEntityFromAggregate($orderItemEntity, $orderItem);
                    }
                }
            } else {
                $orderItemEntity = $this->orderItemMapper->createEntityFromAggregate($orderItem, $orderEntity);

                $orderEntity->addOrderItem($orderItemEntity);
            }
        }
    }

    private function doUpdateEntityFromAggregate(OrderEntity $orderEntity, Order $order): void
    {
        $orderEntity
            ->setOrderDate($order->orderDate)
            ->setOrderStatus($order->orderStatus)
            ->setDescription($order->description)
            ->setAddress($this->addressMapper->createEntityFromAggregate($order->address))
            ->setBuyer($order->buyerId ? $this->em->getReference(BuyerEntity::class, $order->buyerId) : null)
            ->setPaymentMethod($order->paymentMethodId ? $this->em->getReference(PaymentMethodEntity::class, $order->paymentMethodId) : null)
        ;
    }

    public function createAggregateFromEntity(OrderEntity $orderEntity): Order
    {
        $reflection = new ReflectionClass(Order::class);

        $order = $reflection->newInstanceWithoutConstructor();

        $reflection->getProperty('id')->setValue($order, $orderEntity->getId());
        $reflection->getProperty('orderDate')->setValue($order, $orderEntity->getOrderDate());
        $reflection->getProperty('orderStatus')->setValue($order, $orderEntity->getOrderStatus());
        $reflection->getProperty('description')->setValue($order, $orderEntity->getDescription());
        $reflection->getProperty('buyerId')->setValue($order, $orderEntity?->getBuyer()?->getId());
        $reflection->getProperty('paymentMethodId')->setValue($order, $orderEntity?->getPaymentMethod()?->getId());
        $reflection->getProperty('address')->setValue($order, $this->addressMapper->createAggregateFromEntity($orderEntity->getAddress()));

        // Order items
        $orderItems = [];
        foreach ($orderEntity->getOrderItems() as $orderItemEntity) {
            $orderItems[] = $this->orderItemMapper->createAggregateFromEntity($orderItemEntity);
        }

        $reflection->getProperty('orderItems')->setValue($order, $orderItems);

        return $order;
    }
}
