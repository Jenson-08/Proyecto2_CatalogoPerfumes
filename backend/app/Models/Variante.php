<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variante extends Model
{
    protected $table = 'variantes';

    protected $fillable = ['producto_id', 'presentacion', 'precio_usd', 'stock'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
