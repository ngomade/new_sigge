<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The application's global command schedule.
     *
     * This schedule defines the commands that should be run on a daily,
     * weekly, or monthly basis.
     */
    protected function schedule(Schedule $schedule)
    {
        // Les tâches planifiées sont définies dans les Service Providers
        $schedule->command('equipements:update-etats')->hourly();
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
