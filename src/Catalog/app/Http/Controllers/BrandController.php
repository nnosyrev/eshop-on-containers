<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use OpenApi\Attributes as OA;

class BrandController extends Controller
{
    #[OA\Get(path: '/api/brands', summary: 'List catalog item brands', tags: ['Brands'])]
    #[OA\Response(
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: BrandResource::class)
        )
    )]
    public function index()
    {
        $brands = Brand::orderBy('name')->get();

        return BrandResource::collection($brands);
    }
}
