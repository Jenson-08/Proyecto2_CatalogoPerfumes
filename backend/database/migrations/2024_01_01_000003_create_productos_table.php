<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('sku')->unique();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->foreignId('coleccion_id')->constrained('colecciones')->cascadeOnDelete();
            $table->text('descripcion')->nullable();
            $table->string('notas_olfativas')->nullable();
            $table->string('perfil_olfativo')->nullable();
            $table->string('genero')->default('unisex');
            $table->boolean('edicion_limitada')->default(false);
            $table->string('imagen_url')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
