<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class BusquedaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->string('q')->trim();

        $productos = Producto::query()
            ->where('activo', true)
            ->with('variantes', 'categoria')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('nombre', 'like', "%{$query}%")
                        ->orWhere('descripcion', 'like', "%{$query}%");
                });
            })
            ->paginate(12)
            ->withQueryString();

        return view('busqueda.index', compact('productos', 'query'));
    }
}
