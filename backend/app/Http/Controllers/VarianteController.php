<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Variante;
use App\Http\Requests\VarianteRequest;

class VarianteController extends Controller
{
    public function create(Producto $producto)
    {
        return view('variantes.create', compact('producto'));
    }

    public function store(VarianteRequest $request, Producto $producto)
    {
        $producto->variantes()->create($request->validated());

        return redirect()
            ->route('productos.show', $producto)
            ->with('success', 'Variante agregada correctamente.');
    }

    public function edit(Producto $producto, Variante $variante)
    {
        abort_if($variante->producto_id !== $producto->id, 404);

        return view('variantes.edit', compact('producto', 'variante'));
    }

    public function update(VarianteRequest $request, Producto $producto, Variante $variante)
    {
        abort_if($variante->producto_id !== $producto->id, 404);

        $variante->update($request->validated());

        return redirect()
            ->route('productos.show', $producto)
            ->with('success', 'Variante actualizada correctamente.');
    }

    public function destroy(Producto $producto, Variante $variante)
    {
        abort_if($variante->producto_id !== $producto->id, 404);

        $variante->delete();

        return redirect()
            ->route('productos.show', $producto)
            ->with('success', 'Variante eliminada.');
    }
}
