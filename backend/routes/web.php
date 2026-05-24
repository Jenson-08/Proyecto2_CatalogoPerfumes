<?php

use Illuminate\Support\Facades\Route;

// The frontend (Vue SPA) is served separately.
// This fallback is only for API discovery during development.
Route::get('/{any?}', function () {
    return response()->json(['message' => 'Perfumes Catalog API. Use /api/* endpoints.']);
})->where('any', '.*');
