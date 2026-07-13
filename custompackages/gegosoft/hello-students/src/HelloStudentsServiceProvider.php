<?php

namespace Gegosoft\HelloStudents;

use Illuminate\Support\ServiceProvider;

class HelloStudentsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish Migrations
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'hello-students-migrations');

        // Publish Views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views'),
        ], 'hello-students-views');

        // Publish Routes — one file per portal
        $this->publishes([
            __DIR__.'/../routes/student.php' => base_path('routes/ghello-students-student.php'),
        ], 'hello-students-routes');

        // Publish components
        $this->publishes([
            __DIR__.'/../resources/assets/' => resource_path('assets'),
        ], 'hello-students-components');
    }

    public function register()
    {
        // Register package services if needed
    }
}
