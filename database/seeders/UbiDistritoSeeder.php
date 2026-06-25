<?php

namespace Database\Seeders;

use App\Models\UbiDistrito;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbiDistritoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $filePath = public_path('data_ubi.txt');

    if (!file_exists($filePath)) {
      $this->command->error('File data_ubi.txt not found in public directory');
      return;
    }

    // CORREGIDO: Desactivar restricciones según el motor de base de datos activo
    $driver = DB::getDriverName();
    if ($driver === 'mysql') {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    } elseif ($driver === 'pgsql') {
      DB::statement("SET session_replication_role = 'replica';");
    }

    $file = fopen($filePath, 'r');
    $header = fgetcsv($file, 0, '|'); // Skip header line

    $batchSize = 1000;
    $batch = [];
    $count = 0;

    while (($row = fgetcsv($file, 0, '|')) !== false) {
      if (count($row) === 5) {
        $batch[] = [
          'id' => trim($row[0]),
          'nombre' => trim($row[1]),
          'info_busqueda' => trim($row[2]),
          'provincia_id' => trim($row[3]),
          'region_id' => trim($row[4]),
        ];

        $count++;

        if (count($batch) >= $batchSize) {
          UbiDistrito::insert($batch);
          $batch = [];
          $this->command->info("Inserted batch of {$batchSize} records. Total: {$count}");
        }
      }
    }

    // Insert remaining records
    if (!empty($batch)) {
      UbiDistrito::insert($batch);
    }

    fclose($file);

    // CORREGIDO: Reactivar las restricciones según el motor de base de datos activo
    if ($driver === 'mysql') {
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    } elseif ($driver === 'pgsql') {
      DB::statement("SET session_replication_role = 'origin';");
    }

    $this->command->info("Successfully imported {$count} districts from data_ubi.txt");
  }
}
