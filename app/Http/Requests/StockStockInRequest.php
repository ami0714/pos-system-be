<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockStockInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stock' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'string', 'in:IN,OUT,ADJUST,SALE'],
            'note' => ['nullable', 'string', 'max:255'],
            'sell_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
