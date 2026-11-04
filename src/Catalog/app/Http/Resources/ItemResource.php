<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', example: '2'),
        new OA\Property(property: 'name', example: 'Name'),
        new OA\Property(property: 'description', example: 'Description'),
        new OA\Property(property: 'price', example: '129.99'),
        new OA\Property(property: 'available_stock', example: 30),
    ]
)]
class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'available_stock' => $this->available_stock,
        ];
    }
}
