<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'string', 'in:ALL,IN,OUT,ADJUST,SALE'],
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date', 'after_or_equal:startDate'],
        ];
    }
}
