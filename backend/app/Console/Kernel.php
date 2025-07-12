<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * Note: Les tâches planifiées des laboratoires sont maintenant gérées
     * dans le LaboratoireScheduleServiceProvider pour une meilleure organisation.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Les tâches planifiées sont définies dans les Service Providers
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
