<?php

namespace App\Console;

use App\Console\Commands\Addon\InstallAlumniModule;
use App\Console\Commands\Addon\InstallCertificateModule;
use App\Console\Commands\Addon\InstallChatModule;
use App\Console\Commands\Addon\InstallExamModule;
use App\Console\Commands\Addon\InstallFeeModule;
use App\Console\Commands\Addon\InstallInventoryModule;
use App\Console\Commands\Addon\InstallQuizModule;
use App\Console\Commands\Addon\InstallTimetableModule;
use App\Console\Commands\Addon\InstallTransportModule;
use App\Console\Commands\Addon\InstallVideoroomModule;
use App\Console\Commands\AddStandard;
use App\Console\Commands\CheckAnniversary;
use App\Console\Commands\CheckBirthday;
use App\Console\Commands\CheckBirthdayReminder;
use App\Console\Commands\CheckMail;
use App\Console\Commands\CheckNotification;
use App\Console\Commands\CheckSendMail;
use App\Console\Commands\CheckSms;
use App\Console\Commands\CheckSubscription;
use App\Console\Commands\CheckSubscriptionExpired;
use App\Console\Commands\CheckTask;
use App\Console\Commands\CheckWebNotification;
use App\Console\Commands\DataSeeder\SeedAttendance;
use App\Console\Commands\Test\CheckEnv;
use App\Console\Commands\Test\CheckPushNotification;
use App\Console\Commands\Test\CheckTest;
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
        //

        // Test
        CheckEnv::class,
        CheckTest::class,
        CheckPushNotification::class,

        //
        CheckSubscription::class,
        CheckSubscriptionExpired::class,
        CheckMail::class,
        CheckSms::class,
        CheckBirthday::class,
        CheckAnniversary::class,
        CheckBirthdayReminder::class,
        CheckNotification::class,
        CheckWebNotification::class,
        CheckSendMail::class,
        CheckTask::class,

        SeedAttendance::class,

        AddStandard::class,
        InstallAlumniModule::class,
        InstallCertificateModule::class,
        InstallChatModule::class,
        InstallExamModule::class,
        InstallFeeModule::class,
        InstallInventoryModule::class,
        InstallQuizModule::class,
        InstallTimetableModule::class,
        InstallTransportModule::class,
        InstallVideoroomModule::class,
    ];

    /**
     * Define the application's command schedule.
     *
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
