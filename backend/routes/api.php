<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\VarianteController;
use App\Http\Controllers\Api\BusquedaController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index']);
Route::get('/buscar', [BusquedaController::class, 'index']);
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{slug}', [CategoriaController::class, 'show']);
Route::get('/colecciones', [CategoriaController::class, 'colecciones']);
Route::get('/form-data', [ProductoController::class, 'formData']);

Route::apiResource('productos', ProductoController::class);
Route::apiResource('productos.variantes', VarianteController::class)
    ->only(['index', 'store', 'update', 'destroy']);
