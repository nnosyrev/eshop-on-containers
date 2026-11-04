<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'name', example: 'Name'),
        new OA\Property(property: 'description', example: 'Description'),
        new OA\Property(property: 'price', example: '199.99'),
        new OA\Property(property: 'type_id', example: 5),
        new OA\Property(property: 'brand_id', example: 6),
        new OA\Property(property: 'available_stock', example: 10),
        new OA\Property(property: 'restock_threshold', example: 20),
        new OA\Property(property: 'max_stock_threshold', example: 30),
    ]
)]
class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'description' => 'max:1024',
            'price' => 'required|decimal:2',
            'type_id' => 'required|integer|exists:types,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'available_stock' => 'required|integer|min:0',
            'restock_threshold' => 'required|integer|min:0',
            'max_stock_threshold' => 'required|integer|min:0',
        ];
    }
}
