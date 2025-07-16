<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\laboratoires\Equipements;

class UpdateEquipementsEtat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'equipements:update-etats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Met à jour automatiquement l'état de tous les équipements selon entretiens et réservations.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mise à jour des états des équipements...');
        $count = 0;
        foreach (Equipements::all() as $equipement) {
            $equipement->updateEtatAutomatique();
            $count++;
        }
        $this->info("$count équipements mis à jour.");
        return 0;
    }
}
