<?php

namespace App\Domain\Aggregate\Buyer;

final readonly class CardType
{
    public function __construct(public int $id, public string $name) {}
}
