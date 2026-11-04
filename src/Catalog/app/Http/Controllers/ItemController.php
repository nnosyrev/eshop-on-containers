<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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


    #[OA\Post(path: '/api/items', summary: 'Create a catalog item', tags: ['Items'])]
    #[OA\RequestBody(
        required: true,
        description: 'New Item',
        content: new OA\JsonContent(ref: ItemRequest::class)
    )]
    #[OA\Response(
        response: 201,
        description: 'OK',
        content: new OA\JsonContent(ref: ItemResource::class)
    )]
    #[OA\Response(
        response: 422,
        description: 'Unprocessable Content'
    )]
    public function store(ItemRequest $request): ItemResource
    {
        $item = new Item();
        $item->fill($request->validated());
        $item->on_reorder = false;
        $item->save();

        return new ItemResource($item);
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

    #[OA\Delete(path: '/api/items/{id}', summary: 'Delete catalog item', tags: ['Items'])]
    #[OA\Response(
        response: 200,
        description: 'OK',
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
    )]
    public function destroy(#[OA\PathParameter] int $id): Response
    {
        $item = Item::where('id', '=', $id)
            ->firstOrFail();

        $item->delete();

        return response('', 200);
    }
}
