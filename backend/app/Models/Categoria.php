<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nombre', 'slug', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public static function booted(): void
    {
        static::creating(function ($categoria) {
            $categoria->slug = $categoria->slug ?? Str::slug($categoria->nombre);
        });
    }
}
