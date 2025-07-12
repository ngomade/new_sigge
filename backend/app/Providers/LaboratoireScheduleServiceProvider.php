<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class LaboratoireScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $this->schedule($schedule);
        });
    }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Vérifier les alertes des laboratoires tous les jours à 8h00
        $schedule->command('laboratoire:check-alerts')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->description('Vérification quotidienne des alertes des laboratoires');

        // Vérifier les alertes des laboratoires toutes les heures en semaine
        $schedule->command('laboratoire:check-alerts')
            ->weekdays()
            ->hourly()
            ->between('09:00', '18:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->description('Vérification horaire des alertes des laboratoires (semaine)');

        // Nettoyer les invitations expirées tous les jours à 6h00
        $schedule->command('laboratoire:nettoyer-invitations')
            ->dailyAt('06:00')
            ->withoutOverlapping()
            ->runInBackground()
            ->description('Nettoyage automatique des invitations expirées');
    }
}
