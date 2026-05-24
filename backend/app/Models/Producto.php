<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre', 'sku', 'categoria_id', 'coleccion_id',
        'descripcion', 'notas_olfativas', 'perfil_olfativo',
        'genero', 'edicion_limitada', 'imagen_url', 'activo',
    ];

    protected $casts = [
        'edicion_limitada' => 'boolean',
        'activo'           => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function coleccion()
    {
        return $this->belongsTo(Coleccion::class);
    }

    public function variantes()
    {
        return $this->hasMany(Variante::class);
    }

    public function getPrecioDesdeAttribute(): ?string
    {
        $min = $this->variantes->min('precio_usd');
        return $min ? '$'.number_format($min, 2) : null;
    }
}
