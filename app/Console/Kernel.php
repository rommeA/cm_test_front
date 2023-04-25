<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $filePath = storage_path('logs/schedule.log');
        $schedule
             ->command('geoip:download')
             ->daily()
             ->sendOutputTo($filePath);

        $schedule
            ->command('documents:archive-expired')
            ->daily()
            ->sendOutputTo($filePath);

        $schedule
            ->command('members:delete-empty')
            ->daily()
            ->at('16:00');
        if (app()->isProduction()) {
            $schedule->command('backup:run')->daily()->at('22:15');
            $schedule->command('backup:monitor')->daily()->at('10:00');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
