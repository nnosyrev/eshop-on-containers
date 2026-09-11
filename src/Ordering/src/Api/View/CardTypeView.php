<?php

namespace App\Api\View;

final readonly class CardTypeView
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
