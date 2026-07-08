<?php

namespace App\Schoolplus;

use Illuminate\Support\ServiceProvider;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('student', function () {
            return new StudentService;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {}
}
