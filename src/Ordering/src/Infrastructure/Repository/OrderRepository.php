<?php

namespace App\Infrastructure\Repository;

use App\Domain\Aggregate\Order\Address as OrderAddress;
use App\Domain\Aggregate\Order\Order;
use App\Domain\Aggregate\Order\OrderItem;
use App\Domain\Aggregate\Order\OrderRepositoryInterface;
use App\Infrastructure\ORM\Entity\Address;
use App\Infrastructure\ORM\Entity\BuyerEntity;
use App\Infrastructure\ORM\Entity\OrderEntity;
use App\Infrastructure\ORM\Entity\OrderItemEntity;
use App\Infrastructure\ORM\Entity\PaymentMethodEntity;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

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

        // Updating entity properties
        $orderEntity
            ->setOrderDate($order->orderDate)
            ->setOrderStatus($order->orderStatus)
            ->setDescription($order->description)
            ->setAddress(Address::createFromAggregate($order->address))
            ->setPaymentMethod($order->paymentMethodId ? $this->em->getReference(PaymentMethodEntity::class, $order->paymentMethodId) : null)
            ->setBuyer($order->buyerId ? $this->em->getReference(BuyerEntity::class, $order->buyerId) : null)
        ;

        foreach ($order->getOrderItems() as $orderItem) {
            if (!empty($orderItem->id)) {
                foreach ($orderEntity->getOrderItems() as $orderItemEntity) {
                    if ($orderItem->id === $orderItemEntity->getId()) {
                        $orderItemEntity
                            ->setProductId($orderItem->productId)
                            ->setDiscount($orderItem->discount)
                            ->setPictureUrl($orderItem->pictureUrl)
                            ->setProductName($orderItem->productName)
                            ->setUnitPrice($orderItem->unitPrice)
                            ->setUnits($orderItem->units)
                        ;
                    }
                }
            } else {
                $orderItemEntity = OrderItemEntity::createFromAggregate($orderItem, $orderEntity);

                $orderEntity->addOrderItem($orderItemEntity);
            }
        }

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

        $orderReflection = new ReflectionClass(Order::class);

        $order = $orderReflection->newInstanceWithoutConstructor();

        $orderReflection->getProperty('id')->setValue($order, $orderEntity->getId());
        $orderReflection->getProperty('orderDate')->setValue($order, $orderEntity->getOrderDate());
        $orderReflection->getProperty('orderStatus')->setValue($order, $orderEntity->getOrderStatus());
        $orderReflection->getProperty('description')->setValue($order, $orderEntity->getDescription());
        $orderReflection->getProperty('buyerId')->setValue($order, $orderEntity?->getBuyer()?->getId());
        $orderReflection->getProperty('paymentMethodId')->setValue($order, $orderEntity?->getPaymentMethod()?->getId());
        $orderReflection->getProperty('address')->setValue($order, new OrderAddress(
            $orderEntity->getAddress()->street,
            $orderEntity->getAddress()->city,
            $orderEntity->getAddress()->state,
            $orderEntity->getAddress()->country,
            $orderEntity->getAddress()->zipCode,
        ));

        // Order items
        $orderItemReflection = new ReflectionClass(OrderItem::class);

        $orderItems = [];
        foreach ($orderEntity->getOrderItems() as $orderItemEntity) {
            $orderItem = $orderItemReflection->newInstanceWithoutConstructor();

            $orderItemReflection->getProperty('id')->setValue($orderItem, $orderItemEntity->getId());
            $orderItemReflection->getProperty('productId')->setValue($orderItem, $orderItemEntity->getProductId());
            $orderItemReflection->getProperty('productName')->setValue($orderItem, $orderItemEntity->getProductName());
            $orderItemReflection->getProperty('unitPrice')->setValue($orderItem, $orderItemEntity->getUnitPrice());
            $orderItemReflection->getProperty('discount')->setValue($orderItem, $orderItemEntity->getDiscount());
            $orderItemReflection->getProperty('pictureUrl')->setValue($orderItem, $orderItemEntity->getPictureUrl());
            $orderItemReflection->getProperty('units')->setValue($orderItem, $orderItemEntity->getUnits());

            $orderItems[] = $orderItem;
        }

        $orderReflection->getProperty('orderItems')->setValue($order, $orderItems);

        return $order;
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
