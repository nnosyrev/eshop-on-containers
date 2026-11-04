<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'eShop - Catalog HTTP API',
    description: 'The Catalog Microservice HTTP API. This is a Data-Driven/CRUD microservice sample',
)]
#[OA\Schema(
    schema: "PaginatorMeta",
    type: 'object',
    properties: [
        new OA\Property(property: 'current_page', type: 'int'),
        new OA\Property(property: 'from', type: 'int'),
        new OA\Property(property: 'last_page', type: 'int'),
        new OA\Property(
            property: 'links',
            type: 'array',
            items: new OA\Items(properties: [
                new OA\Property(property: 'url', example: 'http://localhost/api/entities?page=1'),
                new OA\Property(property: 'label', example: '&laquo; Previous'),
                new OA\Property(property: 'active', type: 'bool'),
            ])
        ),
        new OA\Property(property: 'path', example: 'http://localhost/api/entities'),
        new OA\Property(property: 'per_page', type: 'int'),
        new OA\Property(property: 'to', type: 'int'),
        new OA\Property(property: 'total', type: 'int'),
    ]
)]
#[OA\Schema(
    schema: "PaginatorLinks",
    type: 'object',
    properties: [
        new OA\Property(property: 'first', example: 'http://localhost/api/entities?page=1'),
        new OA\Property(property: 'last', example: 'http://localhost/api/entities?page=5'),
        new OA\Property(property: 'prev', example: 'http://localhost/api/entities?page=1'),
        new OA\Property(property: 'next', example: 'http://localhost/api/entities?page=3'),
    ]
)]
abstract class Controller
{
    //
}
