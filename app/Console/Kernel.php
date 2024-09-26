<?php

namespace App\Console;

use App\Jobs\ProcessFavorites;
use App\Jobs\ProcessVisits;
use DateTimeZone;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new ProcessVisits)->dailyAt('05:00');
        $schedule->job(new ProcessFavorites)->dailyAt('06:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    public function scheduleTimezone(): DateTimeZone|string|null
    {
        return 'America/Sao_Paulo';
    }
}
