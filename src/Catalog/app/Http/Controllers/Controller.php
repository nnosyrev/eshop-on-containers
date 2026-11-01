<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'eShop - Catalog HTTP API',
    description: 'The Catalog Microservice HTTP API. This is a Data-Driven/CRUD microservice sample',
)]
abstract class Controller
{
    //
}
