<?php

namespace App\Http\Controllers;

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

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $colecciones = Coleccion::where('activo', true)->get();

        return view('productos.create', compact('categorias', 'colecciones'));
    }

    public function store(ProductoRequest $request)
    {
        $producto = Producto::create($request->validated());

        return redirect()
            ->route('productos.show', $producto)
            ->with('success', 'Producto creado correctamente. Ahora agrega sus variantes.');
    }

    public function show(Producto $producto)
    {
        $producto->load('categoria', 'coleccion', 'variantes');

        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::where('activo', true)->get();
        $colecciones = Coleccion::where('activo', true)->get();

        return view('productos.edit', compact('producto', 'categorias', 'colecciones'));
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()
            ->route('productos.show', $producto)
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete(); // variantes se eliminan por cascade

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
