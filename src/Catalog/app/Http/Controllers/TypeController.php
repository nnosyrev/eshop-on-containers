<?php

namespace App\Http\Controllers;

use App\Http\Resources\TypeResource;
use App\Models\Type;
use OpenApi\Attributes as OA;

class TypeController extends Controller
{
    #[OA\Get(path: '/api/types', summary: 'List catalog item types', tags: ['Types'])]
    #[OA\Response(
        response: 200,
        description: 'OK',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: TypeResource::class)
        )
    )]
    public function index()
    {
        $types = Type::orderBy('name')->get();

        return TypeResource::collection($types);
    }
}
