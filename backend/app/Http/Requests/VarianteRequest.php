<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VarianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'presentacion' => 'required|in:EDP 50ml,EDP 100ml',
            'precio_usd'   => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
        ];
    }
}
