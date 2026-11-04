<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ItemController extends Controller
{
    private const DEFAULT_PER_PAGE = 10;

    #[OA\Get(path: '/api/items', summary: 'List catalog items', tags: ['Items'])]
    #[OA\Parameter(name: 'page', in: 'query')]
    #[OA\Parameter(name: 'per_page', in: 'query')]
    #[OA\Response(
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: ItemResource::class)
                ),
                new OA\Property(property: 'links', ref: '#/components/schemas/PaginatorLinks'),
                new OA\Property(property: 'meta', ref: '#/components/schemas/PaginatorMeta'),
            ]
        )
    )]
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', self::DEFAULT_PER_PAGE);

        $items = Item::orderBy('name')->paginate($perPage);

        return ItemResource::collection($items);
    }


    #[OA\Get(path: '/api/items/{id}', summary: 'Get catalog item', tags: ['Items'])]
    #[OA\Response(
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(ref: ItemResource::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
    )]
    public function show(#[OA\PathParameter] int $id): ItemResource
    {
        $item = Item::where('id', '=', $id)
            ->firstOrFail();

        return new ItemResource($item);
    }
}
