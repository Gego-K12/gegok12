<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class School
 *
 * Model for managing school information and relationships.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property int $country_id
 * @property int $state_id
 * @property int $city_id
 * @property string $pincode
 * @property string $slug
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property string $fulladdress
 * @property int $activeMembers
 * @property-read Country $country
 * @property-read State $state
 * @property-read City $city
 * @property-read Collection|SchoolDetail[] $schoolDetail
 * @property-read SchoolDetail $schoolDetailBoard
 * @property-read SchoolDetail $schoolDetailLogo
 * @property-read Collection|AcademicYear[] $academicYear
 * @property-read Collection|StudentAcademic[] $studentAcademic
 * @property-read Collection|Section[] $section
 * @property-read Collection|Standard[] $standard
 * @property-read Collection|User[] $user
 * @property-read Collection|User[] $teacher
 * @property-read Collection|User[] $admin
 * @property-read Collection|Userprofile[] $userprofile
 * @property-read Collection|Events[] $event
 * @property-read Collection|Subscription[] $subscription
 * @property-read Collection|Reminder[] $reminder
 * @property-read Collection|Bulletin[] $bulletin
 * @property-read Collection|SendMail[] $sendMail
 * @property-read Collection|TeacherProfile[] $teacherprofile
 * @property-read Collection|ParentProfile[] $parentprofile
 * @property-read Collection|Promotion[] $promotion
 * @property-read Collection|Discipline[] $discipline
 * @property-read Collection|Attendance[] $attendance
 * @property-read Collection|Timetable[] $timetable
 * @property-read Collection|Assignment[] $assignment
 * @property-read Collection|Subject[] $subject
 * @property-read Collection|LeaveType[] $leaveType
 * @property-read Collection|TeacherLeaveApplication[] $teacherLeaveApplication
 * @property-read Collection|Fee[] $fee
 * @property-read Collection|Document[] $document
 *
 * @mixin \Eloquent
 */
class School extends Model
{
    use HasFactory;

    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schools';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'country_id', 'state_id', 'city_id', 'pincode', 'slug', 'status',
    ];

    /**
     * Get the country for this school.
     *
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    /**
     * Get the state for this school.
     *
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }

    /**
     * Get the city for this school.
     *
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    /**
     * Get school details (metadata).
     *
     * @return HasMany
     */
    public function schoolDetail()
    {
        return $this->hasMany('App\Models\SchoolDetail', 'school_id', 'id');
    }

    /**
     * Get school board detail.
     *
     * @return HasOne
     */
    public function schoolDetailBoard()
    {
        return $this->hasOne('App\Models\SchoolDetail', 'school_id', 'id')->where('meta_key', 'board');
    }

    /**
     * Get school logo detail.
     *
     * @return HasOne
     */
    public function schoolDetailLogo()
    {
        return $this->hasOne('App\Models\SchoolDetail', 'school_id', 'id')->where('meta_key', 'school_logo');
    }

    /**
     * Get academic years for this school.
     *
     * @return HasMany
     */
    public function academicYear()
    {
        return $this->hasMany('App\Models\AcademicYear', 'school_id', 'id');
    }

    /**
     * Get student academic records for this school.
     *
     * @return HasMany
     */
    public function studentAcademic()
    {
        return $this->hasMany('App\Models\StudentAcademic', 'school_id', 'id');
    }

    /**
     * Get sections in this school.
     *
     * @return HasMany
     */
    public function section()
    {
        return $this->hasMany('App\Models\Section', 'school_id', 'id');
    }

    /**
     * Get standards/grades in this school.
     *
     * @return HasMany
     */
    public function standard()
    {
        return $this->hasMany('App\Models\Standard', 'school_id', 'id');
    }

    /**
     * Get all users in this school.
     *
     * @return HasMany
     */
    public function user()
    {
        return $this->hasMany('App\Models\User', 'school_id', 'id');
    }

    /**
     * Get teachers in this school.
     *
     * @return HasMany
     */
    public function teacher()
    {
        return $this->hasMany('App\Models\User', 'school_id', 'id')->where('usergroup_id', 5);
    }

    /**
     * Get admin users in this school.
     *
     * @return HasMany
     */
    public function admin()
    {
        return $this->hasMany('App\Models\User', 'school_id', 'id')->where('usergroup_id', 3);
    }

    /**
     * Get user profiles for users in this school.
     *
     * @return HasMany
     */
    public function userprofile()
    {
        return $this->hasMany('App\Models\Userprofile', 'school_id', 'id');
    }

    /**
     * Get events for this school.
     *
     * @return HasMany
     */
    public function event()
    {
        return $this->hasMany('App\Models\Events');
    }

    /**
     * Get subscriptions for this school.
     *
     * @return HasMany
     */
    public function subscription()
    {
        return $this->hasMany('App\Models\Subscription', 'school_id', 'id');
    }

    /**
     * Get reminders for this school.
     *
     * @return HasMany
     */
    public function reminder()
    {
        return $this->hasMany('App\Models\Reminder', 'school_id', 'id');
    }

    /**
     * Get the full address for this school.
     *
     * @return string
     */
    public function fulladdress()
    {
        $fulladdress = $this->address
                        .'<br/>'.$this->city
                        .'<br />'.$this->pincode
                        .'<br /> '.$this->state
                        .'<br /> '.'India';

        return $fulladdress;

    }

    /**
     * Get bulletins for this school.
     *
     * @return HasMany
     */
    public function bulletin()
    {
        return $this->hasMany('App\Models\Bulletin', 'school_id', 'id');
    }

    /**
     * Get sent mails for this school.
     *
     * @return HasMany
     */
    public function sendMail()
    {
        return $this->hasMany('App\Models\SendMail', 'school_id', 'id');
    }

    /**
     * Scope to filter active schools.
     *
     * @param  Builder  $query
     * @param  int  $school_id
     * @return Builder
     */
    public function scopeIsActive($query, $school_id)
    {
        $query->where(function ($query) use ($school_id) {
            $query->where('id', $school_id)->where('status', 1);
        });

        return $query;
    }

    /**
     * Get count of active members in this school.
     *
     * @return int
     */
    public function getActiveMembersAttribute()
    {
        return $this->hasMany('App\Models\User', 'school_id', 'id')->with('user')->where('usergroup_id', 5)->whereHas('userprofile', function ($query) {
            $query->where('status', 'active');
        })->count();
    }

    /**
     * Get teacher profiles for this school.
     *
     * @return HasMany
     */
    public function teacherprofile()
    {
        return $this->hasMany('App\Models\TeacherProfile', 'school_id', 'id');
    }

    /**
     * Get parent profiles for this school.
     *
     * @return HasMany
     */
    public function parentprofile()
    {
        return $this->hasMany('App\Models\ParentProfile', 'school_id', 'id');
    }

    /**
     * Get promotions for this school.
     *
     * @return HasMany
     */
    public function promotion()
    {
        return $this->hasMany('App\Models\Promotion', 'school_id', 'id');
    }

    /**
     * Get discipline records for this school.
     *
     * @return HasMany
     */
    public function discipline()
    {
        return $this->hasMany('App\Models\Discipline', 'school_id', 'id');
    }

    /**
     * Get attendance records for this school.
     *
     * @return HasMany
     */
    public function attendance()
    {
        return $this->hasMany('\App\Models\Attendance', 'school_id', 'id');
    }

    /**
     * Get timetables for this school.
     *
     * @return HasMany
     */
    public function timetable()
    {
        return $this->hasMany('\App\Models\Timetable', 'school_id', 'id');
    }

    /**
     * Get assignments for this school.
     *
     * @return HasMany
     */
    public function assignment()
    {
        return $this->hasMany('\App\Models\Assignment', 'school_id', 'id');
    }

    /**
     * Get subjects in this school.
     *
     * @return HasMany
     */
    public function subject()
    {
        return $this->hasMany('\App\Models\Subject', 'school_id', 'id');
    }

    /**
     * Get leave types for this school.
     *
     * @return HasMany
     */
    public function leaveType()
    {
        return $this->hasMany('\App\Models\LeaveType', 'school_id', 'id');
    }

    /**
     * Get teacher leave applications for this school.
     *
     * @return HasMany
     */
    public function teacherLeaveApplication()
    {
        return $this->hasMany('\App\Models\TeacherLeaveApplication', 'school_id', 'id');
    }

    /**
     * Get fee records for this school.
     *
     * @return HasMany
     */
    public function fee()
    {
        return $this->hasMany('\App\Models\Fee', 'school_id', 'id');
    }

    /**
     * Get documents for this school.
     *
     * @return HasMany
     */
    public function document()
    {
        return $this->hasMany('\App\Models\Document', 'school_id', 'id');
    }

    /**
     * Get detailed information about school users.
     *
     * @return array
     */
    public function getDetails()
    {
        $array = [];

        $array['admin'] = $this->admin;
        $array['student'] = $this->user->where('usergroup_id', 6)->take(3);
        $array['parent'] = $this->user->where('usergroup_id', 7)->take(3);
        $array['librarian'] = $this->user->where('usergroup_id',8)->take(1);
        $array['receptionist'] = $this->user->where('usergroup_id',10)->take(1);
        $array['accountant'] = $this->user->where('usergroup_id',11)->take(1);

        return $array;
    }
}
