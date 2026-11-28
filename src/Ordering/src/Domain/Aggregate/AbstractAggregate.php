<?php

namespace App\Domain\Aggregate;

abstract class AbstractAggregate
{
    public protected(set) ?int $id = null;

    public protected(set) array $domainEvents = [];

    public function addDomainEvent($domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }
}
