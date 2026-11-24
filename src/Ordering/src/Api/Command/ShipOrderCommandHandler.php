<?php

namespace App\Api\Command;

use App\Domain\Aggregate\Order\OrderRepositoryInterface;

final readonly class ShipOrderCommandHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function handle(ShipOrderCommand $command): bool
    {
        $order = $this->orderRepository->findById($command->orderId);

        if (empty($order)) {
            return false;
        }

        $order->setShippedStatus();

        $this->orderRepository->update($order);

        return true;
    }
}
