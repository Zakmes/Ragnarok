<?php

namespace App\Console;

use App\Domains\Users\Commands\RemoveUsersCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [RemoveUsersCommand::class];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('schedule-monitor:sync')->dailyAt('04:56')->monitorName('Synchronize the scheduler monitor');
        $schedule->command('schedule-monitor:clean')->dailyAt('04:23')->monitorName('Cleanup the old scheduler monitor records');
        $schedule->command(RemoveUsersCommand::class)->daily()->monitorName('Permanently remove users that are marked for deletion');
        // $schedule->job('test')->monitorName('Permanently remove personal access token that are marked for deletion.');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
