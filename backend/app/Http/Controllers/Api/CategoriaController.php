<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Coleccion;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('activo', true)->get();
        return response()->json($categorias, 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function show(string $slug)
    {
        $categoria = Categoria::where('slug', $slug)
            ->where('activo', true)
            ->firstOrFail();

        $productos = $categoria->productos()
            ->where('activo', true)
            ->with('variantes', 'categoria')
            ->paginate(12);

        // Append precio_desde accessor to each product
        $productos->getCollection()->each(function ($p) {
            $p->append('precio_desde');
        });

        return response()->json([
            'categoria' => $categoria,
            'productos' => $productos,
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function colecciones()
    {
        $colecciones = Coleccion::where('activo', true)->get();
        return response()->json($colecciones, 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
