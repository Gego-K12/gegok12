<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use App\Observers\TeacherProfileObserver;
use App\Observers\AcademicYearObserver;
use App\Observers\StandardLinkObserver;
use Illuminate\Support\ServiceProvider;

use App\Observers\UserprofileObserver;

use Laravel\Dusk\DuskServiceProvider;
use App\Observers\HomeworkObserver;
use App\Observers\BulletinObserver;

use App\Observers\SchoolObserver;
use App\Observers\EventObserver;
use App\Observers\TaskObserver;
use App\Observers\UserObserver;

use App\Models\TeacherProfile;
use App\Models\AcademicYear;
use App\Models\StandardLink;

use App\Models\Userprofile;

use App\Models\Bulletin;

use App\Models\Homework;
use App\Models\Setting;
use App\Models\Events;
use App\Models\School;

use App\Models\User;
use App\Models\Task;
use Schema;
use Config;
use App;
use Illuminate\Pagination\Paginator;
// Importing DuskServiceProvider class

class AppServiceProvider extends ServiceProvider {
    /**
    * Bootstrap any application services.
    *
    * @return void
    */

    public function boot() {

        if (app()->environment('production') && !app()->runningInConsole()) {
            $host = request()->getHost();
            if (!in_array($host, ['localhost', '127.0.0.1'])) {
                \URL::forceScheme('https');
            }
        }
     Validator::extend('check_logoutdevice_id', function ($attribute, $value, $parameters, $validator)
        {
            $inputs = $validator->getData();
            $email = $inputs['email'];
            $user = User::where('mobile_no', $email)->where('usergroup_id',7)->first();

            if ($user!=null)
            {
                if ($user->device_id == null)
                {

                    return true;
                }
                return false;
            }
        });

        Validator::extend('teacher_logoutdevice_id', function ($attribute, $value, $parameters, $validator)
        {
            $inputs = $validator->getData();
            $email = $inputs['email'];
            $user = User::where('mobile_no', $email)->where('usergroup_id',5)->first();

            if ($user!=null)
            {
                if ($user->device_id == null)
                {

                    return true;
                }
                return false;
            }
        });

        foreach ([\App\Models\StandardLink::class => 'id', \App\Models\StudentAcademic::class => 'standardLink_id',
            \App\Models\Homework::class => 'standardLink_id', \App\Models\Assignment::class => 'standardLink_id',
            \App\Models\Teacherlink::class => 'standardLink_id'] as $model => $column) {
            $model::addGlobalScope(new \App\Models\Scopes\AcademyTeacherScope($column));
        }

        Events::observe( EventObserver::class );
        Bulletin::observe( BulletinObserver::class );
        Homework::observe( HomeworkObserver::class );
        Userprofile::observe( UserprofileObserver::class );
        TeacherProfile::observe( TeacherProfileObserver::class );
        School::observe( SchoolObserver::class );



        StandardLink::observe(StandardLinkObserver::class);
        $forgetGroups = function ($link) {
            \Illuminate\Support\Facades\Cache::forget('standardLink'.$link->school_id.':'.$link->academic_year_id);
        };
        StandardLink::saved($forgetGroups);
        StandardLink::deleted($forgetGroups);
        StandardLink::restored($forgetGroups);
        User::observe(UserObserver::class);
        Task::observe(TaskObserver::class);
        AcademicYear::observe(AcademicYearObserver::class); //new

        //hide to receive mail
        if ( version_compare( PHP_VERSION, '7.2.0', '>=' ) ) {
            // Ignores notices and reports all other kinds... and warnings
            error_reporting( E_ALL ^ E_NOTICE ^ E_WARNING );
            // error_reporting( E_ALL ^ E_WARNING );
            // Maybe this is enough
        }
        //

        // Skip database operations if app is not installed yet
        if ( !file_exists( storage_path( 'installed' ) ) || !file_exists( base_path( '.env' ) ) ) {
            return;
        }

        if (!\App::runningInConsole()) {
            foreach (app(\App\Services\ApplicationSettings::class)->values() as $key => $value) {
                Config::set('settings.'.$key, $value);
            }
        }

        Paginator::useBootstrap();
    }

    /**
    * Register any application services.
    *
    * @return void
    */
    public function register() {
        $this->app->scoped(\App\Services\AcademyContext::class);
    }
}
