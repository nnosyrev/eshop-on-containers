<?php

namespace App\Infrastructure\ORM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'buyers')]
class BuyerEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue('SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200, unique: true)]
    private ?string $identityGuid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: PaymentMethodEntity::class, mappedBy: 'buyer')]
    private Collection $paymentMethods;

    public function __construct()
    {
        $this->paymentMethods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentityGuid(): ?string
    {
        return $this->identityGuid;
    }

    public function setIdentityGuid(string $identityGuid): static
    {
        $this->identityGuid = $identityGuid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, PaymentMethodEntity>
     */
    public function getPaymentMethods(): Collection
    {
        return $this->paymentMethods;
    }
}
