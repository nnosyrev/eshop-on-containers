<?php

namespace App\Domain\Aggregate\Order;

use DateTime;
use Exception;

class Order
{
    private DateTime $orderDate;

    private OrderStatus $orderStatus;

    private string $description;

    private array $orderItems = [];

    public function __construct(
        string $userId,
        string $userName,
        public readonly Address $address,
        int $cardTypeId,
        string $cardNumber,
        string $cardSecurityNumber,
        string $cardHolderName,
        DateTime $cardExpiration,
        public readonly ?int $buyerId = null,
        public readonly ?int $paymentId = null,
    ) {
        $this->orderDate = new DateTime();
        $this->orderStatus = OrderStatus::Submitted;
    }

    public function addOrderItem(
        int $productId,
        string $productName,
        float $unitPrice,
        float $discount,
        string $pictureUrl,
        int $units = 1
    ): void {
        $orderItem = new OrderItem($productId, $productName, $unitPrice, $discount, $pictureUrl, $units);

        $this->orderItems[] = $orderItem;
    }

    public function setPaymentMethodVerified(int $buyerId, int $paymentId): void
    {
        $this->buyerId = $buyerId;
        $this->paymentId = $paymentId;
    }

    public function setAwaitingValidationStatus(): void
    {
        if ($this->orderStatus === OrderStatus::Submitted) {
            // AddDomainEvent

            $this->orderStatus = OrderStatus::AwaitingValidation;
        }
    }

    public function setStockConfirmedStatus(): void
    {
        if ($this->orderStatus === OrderStatus::AwaitingValidation) {
            // AddDomainEvent

            $this->orderStatus = OrderStatus::StockConfirmed;
            $this->description = 'All the items were confirmed with available stock.';
        }
    }

    public function setPaidStatus(): void
    {
        if ($this->orderStatus === OrderStatus::StockConfirmed) {
            // AddDomainEvent

            $this->orderStatus = OrderStatus::Paid;
            $this->description = 'The payment was performed at a simulated "American Bank checking bank account ending on XX35071"';
        }
    }

    public function setShippedStatus(): void
    {
        if ($this->orderStatus !== OrderStatus::Paid) {
            $this->statusChangeException(OrderStatus::Shipped);
        }

        $this->orderStatus = OrderStatus::Shipped;
        $this->description = 'The order was shipped.';

        // AddDomainEvent
    }

    public function setCancelledStatus(): void
    {
        if ($this->orderStatus === OrderStatus::Paid || $this->orderStatus === OrderStatus::Shipped) {
            $this->statusChangeException(OrderStatus::Cancelled);
        }

        $this->orderStatus = OrderStatus::Cancelled;
        $this->description = 'The order was cancelled.';

        // AddDomainEvent
    }

    private function statusChangeException(OrderStatus $orderStatusToChange): void
    {
        throw new Exception(sprintf('Is not possible to change the order status from %s to %s.', $this->orderStatus->value, $orderStatusToChange->value));
    }
}
