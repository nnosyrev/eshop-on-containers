<?php

namespace App\Domain\Aggregate\Buyer;

use App\Domain\Aggregate\AbstractAggregate;

final class CardType extends AbstractAggregate
{
    public function __construct(public readonly string $name) {}
}
