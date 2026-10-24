<?php

namespace App\Domain\Aggregate\Order;

use App\Domain\Aggregate\AbstractAggregate;
use App\Domain\Aggregate\AggregateRootInterface;
use App\Domain\Aggregate\Buyer\Buyer;
use App\Domain\Aggregate\Buyer\PaymentMethod;
use App\Domain\DomainEvent\OrderCancelledDomainEvent;
use App\Domain\DomainEvent\OrderShippedDomainEvent;
use App\Domain\DomainEvent\OrderStartedDomainEvent;
use App\Domain\DomainEvent\OrderStatusChangedToAwaitingValidationDomainEvent;
use App\Domain\DomainEvent\OrderStatusChangedToPaidDomainEvent;
use App\Domain\DomainEvent\OrderStatusChangedToStockConfirmedDomainEvent;
use DateTimeImmutable;
use Exception;

class Order extends AbstractAggregate implements AggregateRootInterface
{
    private DateTimeImmutable $orderDate;

    private OrderStatus $orderStatus;

    private string $description;

    /**
     * @var OrderItem[]
     */
    private iterable $orderItems = [];

    private Buyer $buyer;
    private PaymentMethod $paymentMethod;

    public function __construct(
        string $userId,
        string $userName,
        public readonly Address $address,
        int $cardTypeId,
        string $cardNumber,
        string $cardSecurityNumber,
        string $cardHolderName,
        DateTimeImmutable $cardExpiration,
        public readonly ?int $buyerId = null,
        public readonly ?int $paymentMethodId = null,
    ) {
        $this->orderDate = new DateTimeImmutable();
        $this->orderStatus = OrderStatus::Submitted;

        $orderStartedDomainEvent = new OrderStartedDomainEvent(
            $this,
            $userId,
            $userName,
            $cardTypeId,
            $cardNumber,
            $cardSecurityNumber,
            $cardHolderName,
            $cardExpiration
        );

        $this->addDomainEvent($orderStartedDomainEvent);
    }

    public function addOrderItem(
        int $productId,
        string $productName,
        float $unitPrice,
        float $discount,
        string $pictureUrl,
        int $units = 1
    ): void {
        $orderItem = new OrderItem($this, $productId, $productName, $unitPrice, $discount, $pictureUrl, $units);

        $this->orderItems[] = $orderItem;
    }

    public function setPaymentMethodVerified(int $buyerId, int $paymentMethodId): void
    {
        $this->buyerId = $buyerId;
        $this->paymentMethodId = $paymentMethodId;
    }

    public function setAwaitingValidationStatus(): void
    {
        if ($this->orderStatus === OrderStatus::Submitted) {
            $this->addDomainEvent(new OrderStatusChangedToAwaitingValidationDomainEvent($this->id, $this->orderItems));

            $this->orderStatus = OrderStatus::AwaitingValidation;
        }
    }

    public function setStockConfirmedStatus(): void
    {
        if ($this->orderStatus === OrderStatus::AwaitingValidation) {
            $this->addDomainEvent(new OrderStatusChangedToStockConfirmedDomainEvent($this->id));

            $this->orderStatus = OrderStatus::StockConfirmed;
            $this->description = 'All the items were confirmed with available stock.';
        }
    }

    public function setPaidStatus(): void
    {
        if ($this->orderStatus === OrderStatus::StockConfirmed) {
            $this->addDomainEvent(new OrderStatusChangedToPaidDomainEvent($this->id, $this->orderItems));

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

        $this->addDomainEvent(new OrderShippedDomainEvent($this));
    }

    public function setCancelledStatus(): void
    {
        if ($this->orderStatus === OrderStatus::Paid || $this->orderStatus === OrderStatus::Shipped) {
            $this->statusChangeException(OrderStatus::Cancelled);
        }

        $this->orderStatus = OrderStatus::Cancelled;
        $this->description = 'The order was cancelled.';

        $this->addDomainEvent(new OrderCancelledDomainEvent($this));
    }

    private function statusChangeException(OrderStatus $orderStatusToChange): void
    {
        throw new Exception(\sprintf('Is not possible to change the order status from %s to %s.', $this->orderStatus->value, $orderStatusToChange->value));
    }
}
