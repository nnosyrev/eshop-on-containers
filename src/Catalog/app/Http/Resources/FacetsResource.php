<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'brand_counts',
            type: 'array',
            items: new OA\Items(ref: FacetCountResource::class)
        ),
        new OA\Property(
            property: 'type_counts',
            type: 'array',
            items: new OA\Items(ref: FacetCountResource::class)
        ),
        new OA\Property(property: 'brand_total', example: 20),
        new OA\Property(property: 'type_total', example: 30),
    ]
)]
class FacetsResource extends JsonResource
{
    private array $brandCounts;

    private array $typeCounts;

    private int $brandTotal;

    private int $typeTotal;

    public function setBrandCounts(array $brandCounts): self
    {
        $this->brandCounts = $brandCounts;

        return $this;
    }

    public function setTypeCounts(array $typeCounts): self
    {
        $this->typeCounts = $typeCounts;

        return $this;
    }

    public function setBrandTotal(int $brandTotal): self
    {
        $this->brandTotal = $brandTotal;

        return $this;
    }

    public function setTypeTotal(int $typeTotal): self
    {
        $this->typeTotal = $typeTotal;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'brand_counts' => FacetCountResource::collection($this->brandCounts),
            'type_counts' => FacetCountResource::collection($this->typeCounts),
            'brand_total' => $this->brandTotal,
            'type_total' => $this->typeTotal,
        ];
    }
}
