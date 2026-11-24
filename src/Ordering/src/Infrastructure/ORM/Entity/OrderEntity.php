<?php

namespace App\Infrastructure\ORM\Entity;

use App\Domain\Aggregate\Order\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class OrderEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue('SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: OrderStatus::class)]
    private OrderStatus $orderStatus;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $orderDate = null;

    #[ORM\Embedded(class: Address::class)]
    private Address $address;

    #[ORM\OneToMany(targetEntity: OrderItemEntity::class, mappedBy: 'order')]
    private Collection $orderItems;

    #[ORM\ManyToOne(targetEntity: BuyerEntity::class)]
    private ?BuyerEntity $buyer;

    #[ORM\ManyToOne(targetEntity: PaymentMethodEntity::class)]
    private ?PaymentMethodEntity $paymentMethod;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getOrderStatus(): OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(OrderStatus $orderStatus): static
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeImmutable $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, OrderItemEntity>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItemEntity $orderItemEntity): self
    {
        $this->orderItems->add($orderItemEntity);

        return $this;
    }

    public function getBuyer(): ?BuyerEntity
    {
        return $this->buyer;
    }

    public function setBuyer(?BuyerEntity $buyerEntity): self
    {
        $this->buyer = $buyerEntity;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethodEntity
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethodEntity $paymentMethodEntity): self
    {
        $this->paymentMethod = $paymentMethodEntity;

        return $this;
    }
}
