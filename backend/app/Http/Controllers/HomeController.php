<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('activo', true)
            ->withCount(['productos' => fn($q) => $q->where('activo', true)])
            ->get();

        return view('home.index', compact('categorias'));
    }
}
