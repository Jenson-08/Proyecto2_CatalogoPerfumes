<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Coleccion;
use App\Http\Requests\ProductoRequest;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria', 'coleccion', 'variantes')
            ->latest()
            ->paginate(15);

        $productos->getCollection()->each(fn($p) => $p->append('precio_desde'));

        return response()->json($productos, 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function store(ProductoRequest $request)
    {
        $producto = Producto::create($request->validated());
        $producto->load('categoria', 'coleccion', 'variantes');
        $producto->append('precio_desde');

        return response()->json($producto, 201, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function show(Producto $producto)
    {
        $producto->load('categoria', 'coleccion', 'variantes');
        $producto->append('precio_desde');

        return response()->json($producto, 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        $producto->load('categoria', 'coleccion', 'variantes');
        $producto->append('precio_desde');

        return response()->json($producto, 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json(['message' => 'Producto eliminado correctamente.']);
    }

    /**
     * Return categorias + colecciones needed for the product form.
     */
    public function formData()
    {
        return response()->json([
            'categorias'  => Categoria::where('activo', true)->get(),
            'colecciones' => Coleccion::where('activo', true)->get(),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
