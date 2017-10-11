<?php

namespace Digist\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        /** Database commands */
        Commands\Db\Nuke::class,
        Commands\Db\Reset::class,

        /** Cleanup commands */
        Commands\Cleanup::class,
        Commands\Cleanup\ForceDeleteOldContacts::class,

        // Import commands
         Commands\ImporterDatabaseBased::class,
         Commands\ImporterFileBased::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cleanup')->dailyAt('2:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
