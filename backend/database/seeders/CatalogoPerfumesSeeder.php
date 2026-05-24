<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Categoria;
use App\Models\Coleccion;
use App\Models\Producto;
use App\Models\Variante;

class CatalogoPerfumesSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('database/data/CatalogoPerfumesCSV.csv');

        if (! file_exists($csvPath)) {
            $this->command->warn("CSV no encontrado en: {$csvPath}");
            $this->command->warn("Ejecuta: php artisan import:csv --path=/ruta/al/csv");
            return;
        }

        $this->importFromCsv($csvPath);
    }

    /**
     * Ensure string is valid UTF-8, converting from Latin-1 if necessary.
     */
    private function toUtf8(string $value): string
    {
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }
        return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
    }

    public function importFromCsv(string $csvPath): void
    {
        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle, 0, ';');

        $categoriaCache  = [];
        $coleccionCache  = [];
        $productoCache   = [];

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < count($header)) continue;

            $data = array_combine($header, $row);
            // Trim whitespace AND ensure all values are valid UTF-8
            $data = array_map(fn($v) => $this->toUtf8(trim($v)), $data);

            // Normalize category/collection names to avoid duplicate inserts
            // caused by mixed encodings (e.g. "Acu\xe1tico" vs "Acu\xc3\xa1tico")
            $catNombre = $data['categoria'];
            $catSlug   = Str::slug($catNombre);
            if (! isset($categoriaCache[$catSlug])) {
                $categoria = Categoria::firstOrCreate(
                    ['slug' => $catSlug],
                    ['nombre' => $catNombre, 'activo' => true]
                );
                $categoriaCache[$catSlug] = $categoria->id;
            }

            $colNombre = $data['coleccion'];
            $colSlug   = Str::slug($colNombre);
            if (! isset($coleccionCache[$colSlug])) {
                $coleccion = Coleccion::firstOrCreate(
                    ['slug' => $colSlug],
                    ['nombre' => $colNombre, 'activo' => true]
                );
                $coleccionCache[$colSlug] = $coleccion->id;
            }

            // Producto (uno por articulo_id)
            $articuloId = $data['articulo_id'];
            if (! isset($productoCache[$articuloId])) {
                $producto = Producto::firstOrCreate(
                    ['sku' => $data['sku']],
                    [
                        'nombre'          => $data['nombre'],
                        'categoria_id'    => $categoriaCache[$catSlug],
                        'coleccion_id'    => $coleccionCache[$colSlug],
                        'descripcion'     => $data['descripcion'],
                        'notas_olfativas' => $data['notas_olfativas'],
                        'perfil_olfativo' => $data['perfil_olfativo'],
                        'genero'          => $data['genero'],
                        'edicion_limitada'=> strtolower($data['edicion_limitada']) === 'verdadero',
                        'imagen_url'      => $data['imagen_url'],
                        'activo'          => true,
                    ]
                );
                $productoCache[$articuloId] = $producto->id;
            }

            // Variante
            Variante::firstOrCreate(
                [
                    'producto_id'  => $productoCache[$articuloId],
                    'presentacion' => $data['presentacion'],
                ],
                [
                    'precio_usd' => (float) $data['precio_usd'],
                    'stock'      => (int) $data['stock'],
                ]
            );
        }

        fclose($handle);

        $this->command?->info('Importación completada: '.Producto::count().' productos, '.Variante::count().' variantes.');
    }
}
