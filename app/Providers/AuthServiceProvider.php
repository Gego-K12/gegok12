<?php

namespace App\Providers;

use App\Models\Plan;
use App\Policies\ApiTokenPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Airlock\PersonalAccessToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\AcademicYear' => 'App\Policies\AcademicYearPolicy',
        'App\Models\ActivityLog' => 'App\Policies\ActivityLogPolicy',
        'App\Models\User' => 'App\Policies\AdminPolicy',
        'App\Models\City' => 'App\Policies\CityPolicy',
        'App\Models\Query' => 'App\Policies\ContactPolicy',
        'App\Models\Country' => 'App\Policies\CountryPolicy',
        'App\Models\ParentProfile' => 'App\Policies\ParentProfilePolicy',
        'App\Models\User' => 'App\Policies\ParentUserPolicy',
        'App\Models\Plan' => 'App\Policies\PlanPolicy',
        'App\Models\Qualification' => 'App\Policies\QualificationPolicy',
        'App\Models\School' => 'App\Policies\SchoolPolicy',
        'App\Models\Section' => 'App\Policies\SectionPolicy',
        'App\Models\Standard' => 'App\Policies\StandardPolicy',
        'App\Models\StandardLink' => 'App\Policies\StandardLinkPolicy',
        'App\Models\State' => 'App\Policies\StatePolicy',
        'App\Models\StudentAcademic' => 'App\Policies\StudentAcademicPolicy',
        'App\Models\Subscription' => 'App\Policies\SubscriptionPolicy',
        'App\Models\TeacherProfile' => 'App\Policies\TeacherProfilePolicy',
        'App\Models\Usergroup' => 'App\Policies\UsergroupPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Userprofile' => 'App\Policies\UserprofilePolicy',
        PersonalAccessToken::class => ApiTokenPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('event', function ($user, $event) {
            return $user->school_id == $event->school_id;
        });

        Gate::define('member', function ($user, $member) {
            return $user->school_id == $member->school_id;
        });

        Gate::define('academic', function ($user, $academic) {
            return $user->school_id == $academic->school_id;
        });

        Gate::define('homework', function ($user, $homework) {
            return $user->school_id == $homework->school_id;
        });

        Gate::define('document', function ($user, $document) {
            return $user->school_id == $document->school_id;
        });

        Gate::define('fee', function ($user, $fee) {
            return $user->school_id == $fee->school_id;
        });

        Gate::define('notice', function ($user, $notice) {
            return $user->school_id == $notice->school_id;
        });

        Gate::define('post', function ($user, $post) {
            return $user->school_id == $post->school_id;
        });

        Gate::define('post_reply', function ($user, $post) {
            return $user->school_id == $post_reply->post->school_id;
        });

        Gate::define('page', function ($user, $page) {
            return $user->school_id == $page->school_id;
        });

        Gate::define('page_attachment', function ($user, $page_attachment) {
            return $user->school_id == $page_attachment->classRoomPage->school_id;
        });

        Gate::define('subject', function ($user, $subject) {
            return $user->school_id == $subject->school_id;
        });

        Gate::define('exam', function ($user, $exam) {
            return $user->school_id == $exam->school_id;
        });

        Gate::define('book', function ($user, $book) {
            return $user->school_id == $book->school_id;
        });

        Gate::define('category', function ($user, $category) {
            return $user->school_id == $category->school_id;
        });

        Gate::define('standardlink', function ($user, $standardlink) {
            return $user->school_id == $standardlink->school_id;
        });

        Gate::define('standard', function ($user, $standard) {
            return $user->school_id == $standard->school_id;
        });

        Gate::define('section', function ($user, $section) {
            return $user->school_id == $section->school_id;
        });

        Gate::define('bulletin', function ($user, $bulletin) {
            return $user->school_id == $bulletin->school_id;
        });

        Gate::define('video', function ($user, $video) {
            return $user->school_id == $video->school_id;
        });

        Gate::define('note', function ($user, $note) {
            return $user->school_id == $note->school_id;
        });

        Gate::define('lessonplan', function ($user, $lessonplan) {
            return $user->school_id == $lessonplan->teacherlink->school_id;
        });

        Gate::define('assignment', function ($user, $assignment) {
            return $user->school_id == $assignment->school_id;
        });

        Gate::define('studentassignment', function ($user, $studentassignment) {
            return $user->school_id == $studentassignment->assignment->school_id;
        });

        Gate::define('studentHomework', function ($user, $studentHomework) {
            return $user->school_id == $studentHomework->homework->school_id;
        });

        Gate::define('subscription', function ($user, $subscription) {
            return $user->school_id == $subscription->school_id;
        });

        Gate::define('payment', function ($user, $id) {
            $plans = Plan::pluck('id')->toArray();

            foreach ($plans as $plan) {
                if ($plan == $id) {
                    return true;
                }
            }

        });
    }
}
