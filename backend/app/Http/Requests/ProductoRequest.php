<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $skuUnique = 'unique:productos,sku';
        if ($this->route('producto')) {
            $skuUnique .= ','.$this->route('producto')->id;
        }

        return [
            'nombre'          => 'required|string|max:255',
            'sku'             => ['required', 'string', 'max:50', $skuUnique],
            'categoria_id'    => 'required|exists:categorias,id',
            'coleccion_id'    => 'required|exists:colecciones,id',
            'descripcion'     => 'nullable|string',
            'notas_olfativas' => 'nullable|string|max:255',
            'perfil_olfativo' => 'nullable|string|max:100',
            'genero'          => 'required|in:femenino,masculino,unisex',
            'edicion_limitada'=> 'boolean',
            'imagen_url'      => 'nullable|url|max:500',
            'activo'          => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'edicion_limitada' => $this->boolean('edicion_limitada'),
            'activo'           => $this->has('activo') ? $this->boolean('activo') : true,
        ]);
    }
}
