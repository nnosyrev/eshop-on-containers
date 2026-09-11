<?php

namespace App\Api\Query;

interface OrderQueriesInterface
{
    public function getOrder(int $id): array;

    public function getOrdersFromUser(int $userId): array;

    public function getCardTypes(): array;
}
