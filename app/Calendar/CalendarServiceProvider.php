<?php

namespace App\Calendar;

use Illuminate\Support\ServiceProvider;

/**
 * Class CalendarServiceProvider
 *
 * Service provider responsible for registering
 * the Calendar service within the Laravel
 * service container.
 */
class CalendarServiceProvider extends ServiceProvider
{
    /**
     * Register the calendar service binding.
     *
     * Binds the "calendar" key to the CalendarService
     * so it can be resolved via dependency injection
     * or the Calendar facade.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('calendar', function () {
            return new CalendarService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * This method is intentionally left empty,
     * but can be used for future bootstrapping logic.
     *
     * @return void
     */
    public function boot() {}
}
