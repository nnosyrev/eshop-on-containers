<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Aggregate\Order\OrderItem;
use App\Infrastructure\ORM\Entity\OrderEntity;
use App\Infrastructure\ORM\Entity\OrderItemEntity;
use ReflectionClass;

final readonly class OrderItemMapper
{
    public function createEntityFromAggregate(OrderItem $orderItem, OrderEntity $orderEntity): OrderItemEntity
    {
        $orderItemEntity = new OrderItemEntity()
            ->setId($orderItem->id)
            ->setOrder($orderEntity)
        ;

        $this->updateEntityFromAggregate($orderItemEntity, $orderItem);

        return $orderItemEntity;
    }

    public function updateEntityFromAggregate(OrderItemEntity $orderItemEntity, OrderItem $orderItem): void
    {
        $orderItemEntity
            ->setProductId($orderItem->productId)
            ->setDiscount($orderItem->discount)
            ->setPictureUrl($orderItem->pictureUrl)
            ->setProductName($orderItem->productName)
            ->setUnitPrice($orderItem->unitPrice)
            ->setUnits($orderItem->units)
        ;
    }

    public function createAggregateFromEntity(OrderItemEntity $orderItemEntity): OrderItem
    {
        $reflection = new ReflectionClass(OrderItem::class);

        $orderItem = $reflection->newInstanceWithoutConstructor();

        $reflection->getProperty('id')->setValue($orderItem, $orderItemEntity->getId());
        $reflection->getProperty('productId')->setValue($orderItem, $orderItemEntity->getProductId());
        $reflection->getProperty('productName')->setValue($orderItem, $orderItemEntity->getProductName());
        $reflection->getProperty('unitPrice')->setValue($orderItem, $orderItemEntity->getUnitPrice());
        $reflection->getProperty('discount')->setValue($orderItem, $orderItemEntity->getDiscount());
        $reflection->getProperty('pictureUrl')->setValue($orderItem, $orderItemEntity->getPictureUrl());
        $reflection->getProperty('units')->setValue($orderItem, $orderItemEntity->getUnits());

        return $orderItem;
    }
}
