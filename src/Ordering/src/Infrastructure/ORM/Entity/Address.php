<?php

namespace App\Infrastructure\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class Address
{
    public function __construct(
        #[ORM\Column(nullable: true)]
        public string $street,
        #[ORM\Column(nullable: true)]
        public string $city,
        #[ORM\Column(nullable: true)]
        public string $state,
        #[ORM\Column(nullable: true)]
        public string $country,
        #[ORM\Column(name: 'zipcode', nullable: true)]
        public string $zipCode,
    ) {}
}
