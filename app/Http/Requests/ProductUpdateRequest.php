<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('productId');

        return [
            'barcode' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')->ignore($productId, 'id'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required'],
        ];
    }
}
