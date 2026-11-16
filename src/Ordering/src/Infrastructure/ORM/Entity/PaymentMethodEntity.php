<?php

namespace App\Infrastructure\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payment_methods')]
class PaymentMethodEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue('SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $alias = null;

    #[ORM\Column(length: 200)]
    private ?string $cardHolderName = null;

    #[ORM\Column(length: 25)]
    private ?string $cardNumber = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiration = null;

    #[ORM\ManyToOne(targetEntity: BuyerEntity::class, inversedBy: 'paymentMethods')]
    #[ORM\JoinColumn(name: 'buyer_id', referencedColumnName: 'id', nullable: false)]
    private BuyerEntity $buyer;

    #[ORM\ManyToOne(targetEntity: CardTypeEntity::class)]
    #[ORM\JoinColumn(name: 'card_type_id', referencedColumnName: 'id', nullable: false)]
    private CardTypeEntity $cardType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;

        return $this;
    }

    public function getCardHolderName(): ?string
    {
        return $this->cardHolderName;
    }

    public function setCardHolderName(string $cardHolderName): static
    {
        $this->cardHolderName = $cardHolderName;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getExpiration(): ?\DateTimeImmutable
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTimeImmutable $expiration): static
    {
        $this->expiration = $expiration;

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

    public function getCardType(): ?CardTypeEntity
    {
        return $this->cardType;
    }

    public function setCardType(?CardTypeEntity $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }
}
