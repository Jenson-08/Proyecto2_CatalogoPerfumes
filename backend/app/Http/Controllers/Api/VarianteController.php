<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Variante;
use App\Http\Requests\VarianteRequest;

class VarianteController extends Controller
{
    public function index(Producto $producto)
    {
        return response()->json($producto->variantes);
    }

    public function store(VarianteRequest $request, Producto $producto)
    {
        $variante = $producto->variantes()->create($request->validated());

        return response()->json($variante, 201);
    }

    public function update(VarianteRequest $request, Producto $producto, Variante $variante)
    {
        abort_if($variante->producto_id !== $producto->id, 404);

        $variante->update($request->validated());

        return response()->json($variante);
    }

    public function destroy(Producto $producto, Variante $variante)
    {
        abort_if($variante->producto_id !== $producto->id, 404);

        $variante->delete();

        return response()->json(['message' => 'Variante eliminada.']);
    }
}
