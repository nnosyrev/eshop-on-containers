<?php

namespace App\Api\Query;

interface OrderQueriesInterface
{
    public function getOrder(int $id): array;

    public function getOrdersFromUser(string $userId): array;

    public function getCardTypes(): array;
}
