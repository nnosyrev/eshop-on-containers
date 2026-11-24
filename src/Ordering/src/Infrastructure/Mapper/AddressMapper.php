<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Aggregate\Order\Address;
use App\Infrastructure\ORM\Entity\Address as EntityAddress;

final readonly class AddressMapper
{
    public function createEntityFromAggregate(Address $address): EntityAddress
    {
        return new EntityAddress(
            $address->street,
            $address->city,
            $address->state,
            $address->country,
            $address->zipCode,
        );
    }

    public function createAggregateFromEntity(EntityAddress $entityAddress): Address
    {
        return new Address(
            $entityAddress->street,
            $entityAddress->city,
            $entityAddress->state,
            $entityAddress->country,
            $entityAddress->zipCode,
        );
    }
}
