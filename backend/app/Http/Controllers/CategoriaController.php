<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function show(string $slug)
    {
        $categoria = Categoria::where('slug', $slug)->where('activo', true)->firstOrFail();

        $productos = $categoria->productos()
            ->where('activo', true)
            ->with('variantes')
            ->paginate(12);

        return view('categorias.show', compact('categoria', 'productos'));
    }
}
