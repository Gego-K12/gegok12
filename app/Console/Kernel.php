<?php

namespace App\Console;

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
            \App\Console\Commands\PrepareAcademyDemo::class,
        //

        //Test
            \App\Console\Commands\Test\CheckEnv::class,
            \App\Console\Commands\Test\CheckTest::class,
            \App\Console\Commands\Test\CheckPushNotification::class,

        //
            \App\Console\Commands\CheckSubscription::class,
            \App\Console\Commands\CheckSubscriptionExpired::class,
            \App\Console\Commands\CheckMail::class,
            \App\Console\Commands\CheckSms::class,
            \App\Console\Commands\CheckBirthday::class,
            \App\Console\Commands\CheckAnniversary::class,
            \App\Console\Commands\CheckBirthdayReminder::class,
            \App\Console\Commands\CheckNotification::class,
            \App\Console\Commands\CheckWebNotification::class,
            \App\Console\Commands\CheckSendMail::class,
            \App\Console\Commands\CheckTask::class,

            \App\Console\Commands\DataSeeder\SeedAttendance::class,

            \App\Console\Commands\AddStandard::class,
            \App\Console\Commands\Addon\InstallAlumniModule::class,
            \App\Console\Commands\Addon\InstallCertificateModule::class,
            \App\Console\Commands\Addon\InstallChatModule::class,
            \App\Console\Commands\Addon\InstallExamModule::class,
            \App\Console\Commands\Addon\InstallFeeModule::class,
            \App\Console\Commands\Addon\InstallInventoryModule::class,
            \App\Console\Commands\Addon\InstallQuizModule::class,
            \App\Console\Commands\Addon\InstallTimetableModule::class,
            \App\Console\Commands\Addon\InstallTransportModule::class,
            \App\Console\Commands\Addon\InstallVideoroomModule::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->command('gego:checksubscription')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checksubscriptionexpired')
                 ->daily()
                 ->withoutOverlapping();

        $schedule->command('gego:checkbirthday')
                 ->daily()
                 ->withoutOverlapping();
                 
         $schedule->command('gego:checkbirthdayreminder')
                 ->daily()
                 ->withoutOverlapping();         

        $schedule->command('gego:checkanniversary')
                 ->daily()
                 ->withoutOverlapping(); 

        $schedule->command('gego:checktask')
                 ->everyMinute()
                 ->withoutOverlapping();

        $schedule->command('gego:checknotification')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checksms')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checkmail')
                 ->hourly()
                 ->withoutOverlapping();

        $schedule->command('gego:checksendmail')
                 ->everyMinute()
                 ->withoutOverlapping();
                 
        $schedule->command('gego:checkwebnotification')
                 ->everyMinute()
                 ->withoutOverlapping();
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

