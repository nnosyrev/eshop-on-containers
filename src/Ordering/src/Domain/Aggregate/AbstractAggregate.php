<?php

namespace App\Domain\Aggregate;

abstract class AbstractAggregate
{
    protected ?int $id = null;

    protected array $domainEvents = [];

    public function addDomainEvent($domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }
}
