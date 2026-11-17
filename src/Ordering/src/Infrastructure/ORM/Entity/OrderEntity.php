<?php

namespace App\Infrastructure\ORM\Entity;

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

    #[ORM\Column]
    private ?int $orderStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $orderDate = null;

    #[ORM\Embedded(class: Address::class)]
    private Address $address;

    #[ORM\OneToMany(targetEntity: OrderItemEntity::class, mappedBy: 'order')]
    private Collection $orderItems;

    #[ORM\ManyToOne(targetEntity: BuyerEntity::class)]
    private BuyerEntity $buyer;

    #[ORM\ManyToOne(targetEntity: PaymentMethodEntity::class)]
    private PaymentMethodEntity $paymentMethod;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderStatus(): ?int
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(int $orderStatus): static
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

    /**
     * @return Collection<int, OrderItemEntity>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }
}
