<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Add this line to import your command
use App\Console\Commands\UpdateTableCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected $commands = [
        UpdateTableCommand::class, // Register your custom command here
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // This runs your custom command once daily at midnight
        $schedule->command('update:table')->daily();

        // You can add more scheduled commands here if needed

        $schedule->command('report:send-daily')
        ->dailyAt('23:50')
        ->timezone('Asia/Kolkata');
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
