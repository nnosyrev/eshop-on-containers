<?php

namespace App\Domain\Aggregate\Buyer;

interface BuyerRepository
{
    public function add(Buyer $buyer): Buyer;

    public function update(Buyer $buyer): Buyer;

    public function find(string $buyerIdentityGuid): Buyer;

    public function findById(int $id): Buyer;
}
