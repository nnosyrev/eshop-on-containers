<?php

namespace App\Api\Command;

use App\Domain\Aggregate\Order\Address;
use App\Domain\Aggregate\Order\Order;
use App\Domain\Aggregate\Order\OrderRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateOrderCommandHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function __invoke(CreateOrderCommand $command): bool
    {
        $address = new Address($command->street, $command->city, $command->state, $command->country, $command->zipcode);
        $order = new Order($command->userId, $command->userName, $address, $command->cardTypeId, $command->cardNumber, $command->cardSecurityNumber, $command->cardHolderName, $command->cardExpiration);

        foreach ($command->orderItems as $item) {
            $order->addOrderItem($item->productId, $item->productName, $item->unitPrice, $item->discount, $item->pictureUrl, $item->units);
        }

        $this->orderRepository->add($order);

        return true;
    }
}
