<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('activo', true)
            ->withCount(['productos' => fn($q) => $q->where('activo', true)])
            ->get();

        return response()->json([
            'categorias' => $categorias,
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
