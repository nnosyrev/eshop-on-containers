<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Resources\FacetsResource;
use App\Http\Resources\ItemResource;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class ItemController extends Controller
{
    private const DEFAULT_PER_PAGE = 10;

    #[OA\Get(path: '/api/items', summary: 'List catalog items', tags: ['Items'])]
    #[OA\Parameter(name: 'page', in: 'query', required: true)]
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
    public function index(Request $request): ResourceCollection
    {
        $perPage = (int) $request->query('per_page', self::DEFAULT_PER_PAGE);

        $items = Item::orderBy('name')->paginate($perPage);

        return ItemResource::collection($items);
    }

    #[OA\Get(path: '/api/items/by/name', summary: 'Get catalog items by name', tags: ['Items'])]
    #[OA\Parameter(name: 'name', in: 'query', required: true)]
    #[OA\Parameter(name: 'page', in: 'query', required: true)]
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
    public function indexByName(Request $request): ResourceCollection
    {
        $perPage = (int) $request->query('per_page', self::DEFAULT_PER_PAGE);
        $name = $request->query('name');

        $items = Item::whereLike('name', '%' . $name . '%')
            ->orderBy('name')
            ->paginate($perPage);

        return ItemResource::collection($items);
    }

    #[OA\Get(path: '/api/items/type/{type}/brand/{brand}', summary: 'Get catalog items by type and brand', tags: ['Items'])]
    #[OA\Parameter(name: 'page', in: 'query', required: true)]
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
    public function indexByTypeAndBrand(#[OA\PathParameter] Type $type, #[OA\PathParameter] Brand $brand, Request $request): ResourceCollection
    {
        $perPage = (int) $request->query('per_page', self::DEFAULT_PER_PAGE);

        $items = Item::where('type_id', '=', $type->id)
            ->where('brand_id', '=', $brand->id)
            ->orderBy('name')
            ->paginate($perPage);

        return ItemResource::collection($items);
    }

    #[OA\Get(path: '/api/items/type/all/brand/{brand}', summary: 'List catalog items by brand', tags: ['Items'])]
    #[OA\Parameter(name: 'page', in: 'query', required: true)]
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
    public function indexByBrand(#[OA\PathParameter] Brand $brand, Request $request): ResourceCollection
    {
        $perPage = (int) $request->query('per_page', self::DEFAULT_PER_PAGE);

        $items = Item::where('brand_id', '=', $brand->id)
            ->orderBy('name')
            ->paginate($perPage);

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

    #[OA\Get(path: '/api/items/by/ids', summary: 'Batch get catalog items', tags: ['Items'])]
    #[OA\Parameter(
        name: 'ids[]',
        in: 'query',
        required: true,
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'integer')
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: ItemResource::class)
        )
    )]
    public function showByIds(Request $request): ResourceCollection
    {
        $ids = (array) $request->query('ids', []);

        $items = Item::whereIn('id', $ids)
            ->orderBy('name')
            ->get();

        return ItemResource::collection($items);
    }

    #[OA\Get(path: '/api/items/facets', summary: 'Get catalog facet counts', tags: ['Items'])]
    #[OA\Parameter(
        name: 'type_ids[]',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'integer')
        )
    )]
    #[OA\Parameter(
        name: 'brand_ids[]',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(type: 'integer')
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(ref: FacetsResource::class)
    )]
    public function facets(Request $request): FacetsResource
    {
        // Brand
        $brandCountsBuilder = DB::table('items')
             ->selectRaw('brand_id as id, count(*) as count')
             ->groupBy('brand_id');

        $typeIds = (array) $request->query('type_ids', []);
        if (!empty($typeIds)) {
            $brandCountsBuilder->whereIn('type_id', $typeIds);
        }

        $brandCounts = $brandCountsBuilder->get();

        $brandTotal = $brandCounts->sum(function($item) {
            return $item->count;
        });

        // Type
        $typeCountsBuilder = DB::table('items')
             ->selectRaw('type_id as id, count(*) as count')
             ->groupBy('type_id');

        $brandIds = (array) $request->query('brand_ids', []);
        if (!empty($brandIds)) {
            $typeCountsBuilder->whereIn('brand_id', $brandIds);
        }

        $typeCounts = $typeCountsBuilder->get();

        $typeTotal = $typeCounts->sum(function($item) {
            return $item->count;
        });

        return new FacetsResource(null)
            ->setBrandCounts($brandCounts->toArray())
            ->setTypeCounts($typeCounts->toArray())
            ->setBrandTotal($brandTotal)
            ->setTypeTotal($typeTotal)
        ;
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
