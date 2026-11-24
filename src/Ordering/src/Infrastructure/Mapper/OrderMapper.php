<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Aggregate\Order\Order;
use App\Infrastructure\ORM\Entity\BuyerEntity;
use App\Infrastructure\ORM\Entity\OrderEntity;
use App\Infrastructure\ORM\Entity\PaymentMethodEntity;
use ReflectionClass;

final readonly class OrderMapper
{
    public function __construct(
        private OrderItemMapper $orderItemMapper,
        private AddressMapper $addressMapper,
    ) {}

    public function createEntityFromAggregate(Order $order, ?BuyerEntity $buyerEntity, ?PaymentMethodEntity $paymentMethodEntity): OrderEntity
    {
        $orderEntity = new OrderEntity()
            ->setId($order->id)
            ->setOrderDate($order->orderDate)
            ->setOrderStatus($order->orderStatus)
            ->setDescription($order->description)
            ->setAddress($this->addressMapper->createEntityFromAggregate($order->address))
            ->setBuyer($buyerEntity)
            ->setPaymentMethod($paymentMethodEntity)
        ;

        foreach ($order->getOrderItems() as $orderItem) {
            $orderItemEntity = $this->orderItemMapper->createEntityFromAggregate($orderItem, $orderEntity);

            $orderEntity->addOrderItem($orderItemEntity);
        }

        return $orderEntity;
    }

    public function updateEntityFromAggregate(OrderEntity $orderEntity, Order $order, ?BuyerEntity $buyerEntity, ?PaymentMethodEntity $paymentMethodEntity): void
    {
        $orderEntity
            ->setOrderDate($order->orderDate)
            ->setOrderStatus($order->orderStatus)
            ->setDescription($order->description)
            ->setAddress($this->addressMapper->createEntityFromAggregate($order->address))
            ->setBuyer($buyerEntity)
            ->setPaymentMethod($paymentMethodEntity)
        ;

        foreach ($order->getOrderItems() as $orderItem) {
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
