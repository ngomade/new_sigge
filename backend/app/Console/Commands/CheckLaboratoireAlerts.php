<?php

namespace App\Console\Commands;

use App\Services\LaboratoireAlertService;
use Illuminate\Console\Command;

class CheckLaboratoireAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laboratoire:check-alerts {--laboratoire= : Code du laboratoire spécifique}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les alertes des laboratoires (échéances de projets et maintenances d\'équipements)';

    /**
     * Execute the console command.
     */
    public function handle(LaboratoireAlertService $alertService)
    {
        $this->info('🔍 Vérification des alertes des laboratoires...');

        $codeLab = $this->option('laboratoire');

        if ($codeLab) {
            $this->info("Vérification pour le laboratoire: {$codeLab}");
            // Vérifications spécifiques pour un laboratoire
            $alertService->checkProjetEcheances();
            $alertService->checkMaintenanceEquipements();

            $stats = $alertService->getAlertStats($codeLab);
            $this->displayStats($stats, $codeLab);
        } else {
            $this->info('Vérification pour tous les laboratoires...');
            // Vérifications pour tous les laboratoires
            $alertService->runAllChecks();
            $this->info('✅ Vérifications terminées pour tous les laboratoires');
        }

        $this->info('✅ Vérification des alertes terminée');
    }

    /**
     * Afficher les statistiques des alertes
     */
    private function displayStats($stats, $codeLab)
    {
        $this->newLine();
        $this->info("📊 Statistiques des alertes pour le laboratoire {$codeLab}:");

        $this->table(
            ['Type', 'Urgent', 'Important', 'Total'],
            [
                ['Projets', $stats['projets_urgents'], $stats['projets_importants'], $stats['projets_urgents'] + $stats['projets_importants']],
                ['Maintenances', $stats['maintenances_urgentes'], $stats['maintenances_importantes'], $stats['maintenances_urgentes'] + $stats['maintenances_importantes']],
                ['TOTAL', $stats['total_urgent'], $stats['total_important'], $stats['total_urgent'] + $stats['total_important']],
            ]
        );
    }
}
