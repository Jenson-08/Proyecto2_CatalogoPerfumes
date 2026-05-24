<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Coleccion extends Model
{
    protected $table = 'colecciones';

    protected $fillable = ['nombre', 'slug', 'descripcion', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public static function booted(): void
    {
        static::creating(function ($coleccion) {
            $coleccion->slug = $coleccion->slug ?? Str::slug($coleccion->nombre);
        });
    }
}
