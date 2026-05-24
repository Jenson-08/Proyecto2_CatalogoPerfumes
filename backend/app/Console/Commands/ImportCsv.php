<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\CatalogoPerfumesSeeder;

class ImportCsv extends Command
{
    protected $signature = 'import:csv {--path= : Ruta al archivo CSV (opcional)}';
    protected $description = 'Importa el catálogo de perfumes desde el CSV';

    public function handle(): int
    {
        $path = $this->option('path') ?? base_path('database/data/CatalogoPerfumesCSV.csv');

        if (! file_exists($path)) {
            $this->error("Archivo no encontrado: {$path}");
            return self::FAILURE;
        }

        $this->info("Importando desde: {$path}");

        $seeder = new CatalogoPerfumesSeeder();
        $seeder->setCommand($this);
        $seeder->importFromCsv($path);

        return self::SUCCESS;
    }
}
