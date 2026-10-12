<?php

namespace App\Api\Query;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class OrderQueries implements OrderQueriesInterface
{
    public function __construct(private Connection $conn) {}

    public function getOrder(int $id): array
    {
        $orderData = $this->conn->fetchAssociative(
            'SELECT o.id as order_number, o.order_date as date, o.description as description,
            o.address_city as city, o.address_country as country, o.address_state as state, o.address_street as street, o.address_zipcode as zipcode,
            o.order_status as status,
            SUM(oi.units * oi.unit_price) as total
            FROM "order" o
            LEFT JOIN order_item oi ON o.id = oi.order_id
            WHERE o.id = :id
            GROUP BY o.id',
            ['id' => $id]
        );

        if (empty($orderData)) {
            throw new NotFoundHttpException('Order not found.');
        }

        $orderItemsData = $this->conn->fetchAllAssociative(
            'SELECT oi.product_name, oi.units, oi.unit_price, oi.picture_url
            FROM order_item oi
            WHERE oi.order_id = :id',
            ['id' => $id]
        );

        $orderData['order_items'] = $orderItemsData;

        return $orderData;
    }

    public function getOrdersFromUser(string $userId): array
    {
        return $this->conn->fetchAllAssociative(
            'SELECT o.id as order_number, o.order_date as date, o.order_status as status, SUM(oi.units * oi.unit_price) as total
            FROM "order" o
            LEFT JOIN order_item oi ON  o.id = oi.order_id
            LEFT JOIN buyer ob on o.buyer_id = ob.id
            WHERE ob.identity_guid = :user_id
            GROUP BY o.id, o.order_date, o.order_status
            ORDER BY o.id',
            ['user_id' => $userId]
        );
    }

    public function getCardTypes(): array
    {
        return $this->conn->fetchAllAssociative('SELECT id, name FROM card_type');
    }
}
