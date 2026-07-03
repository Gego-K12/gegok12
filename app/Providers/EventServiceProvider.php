<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        'App\Events\TestEvent' => [
            'App\Listeners\TestEventListener',
        ],

        'App\Events\CalendarEvent' => [
            'App\Listeners\CalendarEventListener',
        ],

        'App\Events\AfterExpiredEvent' => [
            'App\Listeners\AfterExpiredListener',
        ],

        'App\Events\AfterSubscriptionExpiredEvent' => [
            'App\Listeners\AfterSubscriptionExpiredListener',
        ],

        'App\Events\ReminderEvent' => [
            'App\Listeners\ReminderEventListener',
        ],

        'App\Events\SendMessageEvent' => [
            'App\Listeners\SendMessageEventListener',
        ],

        'App\Events\ReminderMailEvent' => [
            'App\Listeners\ReminderMailListener',
        ],

        'App\Events\UserReminderEvent' => [
            'App\Listeners\UserReminderEventListener',
        ],

        'App\Events\BirthdayReminderMailEvent' => [
            'App\Listeners\BirthdayReminderMailEventListener',
        ],

        'App\Events\AbsentReminderMailEvent' => [
            'App\Listeners\AbsentReminderMailEventListener',
        ],

        'App\Events\AdminBirthdayReminderEvent' => [
            'App\Listeners\AdminBirthdayReminderEventListener',
        ],

        'App\Events\PushEvent' => [
            'App\Listeners\PushEventListener',
        ],

        'App\Events\BirthdayPushEvent' => [
            'App\Listeners\BirthdayPushEventListener',
        ],

        'App\Events\VerificationMailEvent' => [
            'App\Listeners\VerificationMailEventListener',
        ],

        'App\Events\SinglePushEvent' => [
            'App\Listeners\SinglePushEventListener',
        ],

        'App\Events\StandardPushEvent' => [
            'App\Listeners\StandardPushEventListener',
        ],

        'App\Events\TeacherPushEvent' => [
            'App\Listeners\TeacherPushEventListener',
        ],

        'App\Events\SendMessageTeacherEvent' => [
            'App\Listeners\SendMessageTeacherEventListener',
        ],

        'App\Events\Notification\SingleNotificationEvent' => [
            'App\Listeners\Notification\SingleNotificationEventListener',
        ],

        'App\Events\Notification\ClassNotificationEvent' => [
            'App\Listeners\Notification\ClassNotificationEventListener',
        ],

        'App\Events\Notification\SchoolNotificationEvent' => [
            'App\Listeners\Notification\SchoolNotificationEventListener',
        ],

        'App\Events\Notification\TeacherNotificationEvent' => [
            'App\Listeners\Notification\TeacherNotificationEventListener',
        ],

        'App\Events\AdmissionApprovalEvent' => [
            'App\Listeners\AdmissionApprovalListener',
        ],

        'App\Events\EmergencyNotificationEvent' => [
            'App\Listeners\EmergencyNotificationEventListener',
        ],

        'App\Events\Notification\TransportNotificationEvent' => [
            'App\Listeners\Notification\TransportNotificationEventListener',
        ],

        'App\Events\TransportNotificationPushEvent' => [
            'App\Listeners\TransportNotificationPushListener',
        ],

        'App\Events\StudentAttendancePushEvent' => [
            'App\Listeners\StudentAttendancePushListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
