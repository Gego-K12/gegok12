<?php

namespace App\Conference;

use Illuminate\Support\ServiceProvider;

class ConferenceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('conference', function () {
            return new ConferenceService;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
