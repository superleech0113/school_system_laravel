<?php

namespace App\Console;

use App\Settings;
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
        \App\Console\Commands\SendReservationReminderToStudents::class,
        \App\Console\Commands\ExpirePasswordResetTokens::class,
        \App\Console\Commands\RefreshAutomatedTags::class,
        \App\Console\Commands\SendZoomMeetingReminderEmail::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('student:send_reservation_reminder')->everyMinute();
        $schedule->command('email:zoom_meeting_notification')->everyMinute();
        $schedule->command('student:refresh_automated_tags')->everyMinute();

        $schedule->command('expire:password_reset_tokens')->hourly();

        $schedule->command('renew_ssl_certitificates')->twiceDaily(0, 12);
        
        $schedule->command('sync_line_access_tokens')->daily();
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
