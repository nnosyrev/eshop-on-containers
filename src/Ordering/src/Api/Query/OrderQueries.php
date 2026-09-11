<?php

namespace App\Api\Query;

use Doctrine\DBAL\Connection;

final class OrderQueries implements OrderQueriesInterface
{
    public function __construct(private Connection $conn) {}

    public function getOrder(int $id): array
    {
        return $this->conn->fetchAllAssociative(
            'SELECT o.id as order_number, o.order_date as date, o.description as description,
            o.address_city as city, o.address_country as country, o.address_state as state, o.address_street as street, o.address_zipcode as zipcode,
            os.name as status,
            oi.product_name as product_name, oi.units as units, oi.unit_price as unit_price, oi.picture_url as picture_url
            FROM order o
            LEFT JOIN order_item oi ON o.id = oi.order_id
            LEFT JOIN order_status os on o.order_status_id = os.id
            WHERE o.id = :id'
        );
    }

    public function getOrdersFromUser(int $userId): array
    {
        return $this->conn->fetchAllAssociative(
            'SELECT o.id as order_number, o.order_date as date, os.name as status, SUM(oi.units * oi.unit_price) as total
            FROM order o
            LEFT JOIN order_item oi ON  o.id = oi.order_id
            LEFT JOIN order_status os on o.order_status_id = os.id
            LEFT JOIN buyer ob on o.buyer_id = ob.id
            WHERE ob.identity_guid = :user_id
            GROUP BY o.id, o.order_date, os.name
            ORDER BY o.id'
        );
    }

    public function getCardTypes(): array
    {
        return $this->conn->fetchAllAssociative('SELECT id, name FROM card_type');
    }
}
