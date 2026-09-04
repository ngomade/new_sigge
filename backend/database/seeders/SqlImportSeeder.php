<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste de tes fichiers SQL dans l'ordre d'exécution
        $fichiers = ['Fichier1.sql', 'Fichier2.sql'];

        foreach ($fichiers as $nomFichier) {
            $path = database_path("sql/{$nomFichier}");

            if (File::exists($path)) {
                $this->command->info("Importation de : {$nomFichier}...");
                
                $sql = File::get($path);
                DB::unprepared($sql);
                
                $this->command->info("{$nomFichier} importé avec succès !");
            } else {
                $this->command->error("Fichier introuvable : {$path}");
            }
        }
    }
}