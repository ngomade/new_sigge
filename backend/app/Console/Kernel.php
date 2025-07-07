<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Vérifier les alertes des laboratoires tous les jours à 8h00
        $schedule->command('laboratoire:check-alerts')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Vérifier les alertes des laboratoires toutes les heures en semaine
        $schedule->command('laboratoire:check-alerts')
            ->weekdays()
            ->hourly()
            ->between('09:00', '18:00')
            ->withoutOverlapping()
            ->runInBackground();
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
