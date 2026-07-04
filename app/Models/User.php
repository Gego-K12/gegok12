<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use App\Helpers\SiteHelper;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Models\Users\AccountantUser;
use App\Models\Users\AdminUser;
// use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\Models\Users\AlumniUser;
use App\Models\Users\LibrarianUser;
use App\Models\Users\ParentUser;
use App\Models\Users\StudentUser;  // LaratrustUserTrait
use App\Models\Users\TeacherUser;
use App\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laracasts\Presenter\PresentableTrait;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;
use Nckg\Impersonate\Traits\CanImpersonate;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class User
 *
 * Core user model for all system users (students, teachers, parents, staff, admins).
 * Handles authentication, roles, profiles, and relationships across the entire application.
 *
 * @property int $id
 * @property int $school_id
 * @property int $usergroup_id
 * @property int|null $ref_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $mobile_no
 * @property bool $is_activated
 * @property string|null $email_verification_code
 * @property bool $email_verified
 * @property \DateTime|null $email_verified_at
 * @property string|null $platform_token
 * @property string|null $remember_token
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read School $school
 * @property-read Userprofile $userprofile
 * @property-read LibraryCard $librarycard
 * @property-read Collection|User[] $members
 * @property-read Collection|StudentParentLink[] $parents
 * @property-read Collection|StudentParentLink[] $children
 * @property-read Usergroup $usergroup
 * @property-read Collection|ActivityLog[] $activitylog
 * @property-read Collection|Notes[] $notes
 * @property-read Collection|Subscription[] $subscription
 * @property-read Collection|StudentAcademic[] $studentAcademic
 * @property-read StudentAcademic $studentAcademicLatest
 * @property-read Collection|Mark[] $marks
 * @property-read Collection|TeacherProfile[] $teacherprofile
 * @property-read Collection|ParentProfile[] $parentprofile
 * @property-read Collection|Discipline[] $disciplineUser
 * @property-read Collection|Discipline[] $disciplineTeacher
 * @property-read StandardLink $standardLink
 * @property-read Collection|Teacherlink[] $teacherlink
 * @property-read Collection|Attendance[] $attendanceUser
 * @property-read Collection|Attendance[] $attendanceAdmin
 * @property-read Collection|BookLending[] $lending
 * @property-read Collection|Assignment[] $assignment
 * @property-read Collection|StudentAssignment[] $studentAssignment
 * @property-read Collection|StudentAssignment[] $teacherAssignment
 * @property-read Collection|TeacherLeaveApplication[] $requestedUser
 * @property-read Collection|TeacherLeaveApplication[] $approvedUser
 * @property-read Collection|Promotion[] $promotion
 * @property-read Collection|Document[] $document
 * @property-read Collection|Authentication[] $authentication
 * @property-read Collection|PostDetail[] $postDetail
 * @property-read Collection|PostComment[] $postComment
 * @property-read Collection|PostCommentDetail[] $postCommentDetail
 * @property-read TransactionAccount $bankdetail
 * @property-read Collection|Payroll[] $payrolls
 * @property-read Collection|PayrollTransaction[] $payrolltransactions
 * @property-read RouteStudent $routeStudent
 * @property-read TeacherProfile $latestTeacherProfile
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia
{
    use CanImpersonate;
    use HasApiTokens;
    use HasFactory;

    use HasRolesAndPermissions;
    // use HasMediaTrait;
    use InteractsWithMedia;
    use Notifiable;
    // LaratrustUserTrait;
    use PresentableTrait;
    use SoftDeletes;

    // ===== USERGROUP CONSTANTS =====
    const SITEADMIN_USERGROUP_ID = 1;

    const SITESUBADMIN_USERGROUP_ID = 2;

    const SCHOOLADMIN_USERGROUP_ID = 3;

    const SCHOOLSUBADMIN_USERGROUP_ID = 4;

    const TEACHER_USERGROUP_ID = 5;

    const STUDENT_USERGROUP_ID = 6;

    const PARENT_USERGROUP_ID = 7;

    const LIBRARIAN_USERGROUP_ID = 8;

    const ALUMNI_USERGROUP_ID = 9;

    const RECEPTIONIST_USERGROUP_ID = 10;

    const ACCOUNTANT_USERGROUP_ID = 11;

    const STOCK_KEEPER_USERGROUP_ID = 12;

    const NON_TEACHING_USERGROUP_ID = 13;

    // ===== PAYROLL TYPE CONSTANTS =====
    const PAYROLL_TYPE_SALARY_ADVANCE = 2;

    const PAYROLL_TYPE_RETURN_ADVANCE = 3;

    protected $presenter = "App\Presenters\UserprofilePresenter";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'usergroup_id', 'ref_id', 'name', 'email', 'password', 'mobile_no', 'is_activated', 'email_verification_code', 'email_verified', 'email_verified_at', 'platform_token', 'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $with = ['userprofile', 'members', 'children', 'parents'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'email_verified_at'];

    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id');
    }

    /**
     * Get the user profile for this user.
     *
     * @return HasOne
     */
    public function userprofile()
    {
        return $this->hasOne('App\Models\Userprofile', 'user_id', 'id');
    }

    /**
     * Get the library card for this user.
     *
     * @return HasOne
     */
    public function librarycard()
    {
        return $this->hasOne('App\Models\LibraryCard', 'user_id', 'id');
    }

    /**
     * Get room links for this user.
     *
     * @return HasMany
     */
    public function roomlinks()
    {
        return $this->hasMany('App\Models\RoomLink', 'user_id', 'id');
    }

    /**
     * Get child users (members) under this user (for parent users).
     *
     * @return HasMany
     */
    public function members()
    {
        return $this->hasMany('App\Models\User', 'ref_id', 'id');
    }

    /**
     * Get parent relationships for this student.
     *
     * @return HasMany
     */
    public function parents()
    {
        return $this->hasMany('App\Models\StudentParentLink', 'student_id', 'id');
    }

    /**
     * Get the father of this student.
     *
     * @return Builder
     */
    public function father()
    {

        $father = $this->whereHas('parentprofile', function ($query) {
            $query->where('relation', 'father');
        });

        return $father;
    }

    /**
     * Get the mother of this student.
     *
     * @return HasMany
     */
    public function mother()
    {

        $mother = $this->members()->whereHas('parentprofile', function ($query) {
            $query->where('relation', 'mother');
        });

        return $mother;
    }

    /**
     * Scope to filter users by parent relation type.
     *
     * @param  Builder  $query
     * @param  int  $student_id
     * @param  string  $relation
     * @return Builder
     */
    public function scopeByRelation($query, $student_id, $relation)
    {

        $query->whereHas('userParent', function ($q) use ($relation) {
            $q->whereHas('parentprofile', function ($qu) use ($relation) {
                $qu->where('relation', $relation);
            });
        });

        return $query;
    }

    /**
     * Get the child relationships for this parent.
     *
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Models\StudentParentLink', 'parent_id', 'id');
    }

    /**
     * Get the user group for this user.
     *
     * @return BelongsTo
     */
    public function usergroup()
    {
        return $this->belongsTo('App\Models\Usergroup', 'usergroup_id');
    }

    /**
     * Get activity logs for this user.
     *
     * @return HasMany
     */
    public function activitylog()
    {
        return $this->hasMany('App\Models\ActivityLog', 'causer_id', 'id');
    }

    /**
     * Get notes associated with this user.
     *
     * @return HasMany
     */
    public function notes()
    {
        return $this->hasMany('App\Models\Notes', 'entity_id', 'id');
    }

    /**
     * Get subscriptions for this user.
     *
     * @return HasMany
     */
    public function subscription()
    {
        return $this->hasMany('App\Models\Subscription', 'user_id', 'id');
    }

    /**
     * Scope to filter users by school.
     *
     * @param  Builder  $query
     * @param  int  $school_id
     * @return Builder
     */
    public function scopeBySchool($query, $school_id)
    {
        $query->where('school_id', $school_id);

        return $query;
    }

    /**
     * Scope to filter users by role/usergroup.
     *
     * @param  Builder  $query
     * @param  int  $usergroup_id
     * @return Builder
     */
    public function scopeByRole($query, $usergroup_id)
    {
        $query->where('usergroup_id', $usergroup_id);

        return $query;
    }

    /**
     * Scope to filter users by profile status.
     *
     * @param  Builder  $query
     * @param  string  $status
     * @return Builder
     */
    public function scopeByStatus($query, $status)
    {
        $query->wherehas('userprofile', function ($query) use ($status) {
            $query->where('status', $status);
        });

        return $query;
    }

    /**
     * Scope to filter users by name.
     *
     * @param  Builder  $query
     * @param  string  $name
     * @return Builder
     */
    public function scopeByName($query, $name)
    {
        $query->where('name', 'LIKE', $name.'%');

        return $query;
    }

    /**
     * Scope to filter users by first name.
     *
     * @param  Builder  $query
     * @param  string  $firstname
     * @return Builder
     */
    public function scopeByFirstName($query, $firstname)
    {
        $query->wherehas('userprofile', function ($query) use ($firstname) {
            $query->where('firstname', 'LIKE', $firstname.'%');
        });

        return $query;
    }

    /**
     * Scope to filter users by last name.
     *
     * @param  Builder  $query
     * @param  string  $lastname
     * @return Builder
     */
    public function scopeByLastName($query, $lastname)
    {
        $query->wherehas('userprofile', function ($query) use ($lastname) {
            $query->where('lastname', 'LIKE', $lastname.'%');
        });

        return $query;
    }

    /**
     * Scope to filter users by gender.
     *
     * @param  Builder  $query
     * @param  string  $gender
     * @return Builder
     */
    public function scopeByGender($query, $gender)
    {
        $query->wherehas('userprofile', function ($query) use ($gender) {
            $query->where('gender', '=', $gender);
        });

        return $query;
    }

    /**
     * Scope to filter users by marital status.
     *
     * @param  Builder  $query
     * @param  string  $marital_status
     * @return Builder
     */
    public function scopeByMaritalStatus($query, $marital_status)
    {
        $query->wherehas('userprofile', function ($query) use ($marital_status) {
            $query->where('marital_status', '=', $marital_status);
        });

        return $query;
    }

    /**
     * Scope to filter users by date of birth month.
     *
     * @param  Builder  $query
     * @param  string  $date_of_birth
     * @return Builder
     */
    public function scopeByDateOfBirth($query, $date_of_birth)
    {
        $query->wherehas('userprofile', function ($query) use ($date_of_birth) {
            $query->where(\DB::raw("(DATE_FORMAT(date_of_birth,'%m'))"), $date_of_birth);
        });

        return $query;
    }

    /**
     * Scope to filter users by mobile number.
     *
     * @param  Builder  $query
     * @param  string  $mobile_no
     * @return Builder
     */
    public function scopeByMobileNo($query, $mobile_no)
    {
        $query->where('mobile_no', 'LIKE', $mobile_no.'%');

        return $query;
    }

    /**
     * Scope to filter users by email.
     *
     * @param  Builder  $query
     * @param  string  $email
     * @return Builder
     */
    public function scopeByEmailId($query, $email)
    {
        $query->where('email', 'LIKE', $email.'%');

        return $query;
    }

    /**
     * Scope to filter users by caste.
     *
     * @param  Builder  $query
     * @param  string  $caste
     * @return Builder
     */
    public function scopeByCaste($query, $caste)
    {
        $query->wherehas('userprofile', function ($query) use ($caste) {
            $query->where('caste', '=', $caste);
        });

        return $query;
    }

    /**
     * Scope to filter users by account status.
     *
     * @param  Builder  $query
     * @param  string  $status
     * @return Builder
     */
    public function scopeByUserStatus($query, $status)
    {
        $query->where('status', 'LIKE', $status.'%');

        return $query;
    }

    /**
     * Get send mail records for this user.
     *
     * @return HasMany
     */
    public function sendMail()
    {
        return $this->hasMany('App\Models\SendMail', 'user_id', 'id');
    }

    /**
     * Get permission user records for this user.
     *
     * @return HasMany
     */
    public function permissionUser()
    {
        return $this->hasMany('App\Models\PermissionUser', 'user_id', 'id');
    }

    /**
     * Get reminders for this user.
     *
     * @return HasMany
     */
    public function userReminder()
    {
        return $this->hasMany('App\Models\Reminder', 'id', 'entity_id')->where('entity_name', '=', 'App\\Models\\User');
    }

    /**
     * Get feedback submitted by parent user.
     *
     * @return HasMany
     */
    public function feedbackParent()
    {
        return $this->hasMany('App\Models\Feedback', 'parent_id', 'id');
    }

    /**
     * Get feedback submitted to admin by this user.
     *
     * @return HasMany
     */
    public function feedbackAdmin()
    {
        return $this->hasMany('App\Models\Feedback', 'admin_id', 'id');
    }

    /**
     * Get student academic records for this user.
     *
     * @return HasMany
     */
    public function studentAcademic()
    {
        return $this->hasMany('App\Models\StudentAcademic', 'user_id', 'id');
    }

    /**
     * Get the latest student academic record for this user.
     *
     * @return HasOne
     */
    public function studentAcademicLatest()
    {
        return $this->hasOne('App\Models\StudentAcademic', 'user_id', 'id')->orderByDesc('id')->limit(1);
    }

    /**
     * Get marks for this user.
     *
     * @return HasMany
     */
    public function marks()
    {
        return $this->hasMany('App\Models\Mark', 'user_id', 'id');
    }

    /**
     * Get teacher profiles for this user.
     *
     * @return HasMany
     */
    public function teacherprofile()
    {
        return $this->hasMany('App\Models\TeacherProfile', 'user_id', 'id');
    }

    /**
     * Get alumni profile for this user.
     *
     * @return HasOne
     */
    public function alumniprofile()
    {
        if (class_exists('Gegok12\Alumni\Models\Alumniprofile')) {
            return $this->hasOne('\Gegok12\Alumni\Models\Alumniprofile', 'user_id', 'id');
        }
    }

    /**
     * Get users reporting to this user.
     *
     * @return HasMany
     */
    public function reportUser()
    {
        return $this->hasMany('App\Models\TeacherProfile', 'reporting_to', 'id');
    }

    /**
     * Get parent profiles for this user.
     *
     * @return HasMany
     */
    public function parentprofile()
    {
        return $this->hasMany('App\Models\ParentProfile', 'user_id', 'id');
    }

    /**
     * Scope to filter users by blood group.
     *
     * @param  Builder  $query
     * @param  string  $blood_group
     * @return Builder
     */
    public function scopeByBloodGroup($query, $blood_group)
    {
        $query->wherehas('userprofile', function ($query) use ($blood_group) {
            $query->where('blood_group', '=', $blood_group);
        });

        return $query;
    }

    /**
     * Scope to filter parents by first name.
     *
     * @param  Builder  $query
     * @param  string  $firstname
     * @return Builder
     */
    public function scopeByFirstNameParent($query, $firstname)
    {
        $query->where('usergroup_id', self::PARENT_USERGROUP_ID)->wherehas('parents', function ($query) use ($firstname) {
            $query->whereHas('userParent', function ($q) use ($firstname) {
                $q->whereHas('userprofile', function ($qu) use ($firstname) {
                    $qu->where('firstname', 'LIKE', $firstname.'%');
                });
            });
        });

        return $query;
    }

    /**
     * Scope to filter parents by full name.
     *
     * @param  Builder  $query
     * @param  string  $fullname
     * @return Builder
     */
    public function scopeByFullNameParent($query, $fullname)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->whereHas('userprofile', function ($qu) use ($fullname) {
                $qu->where('firstname', 'LIKE', $fullname.'%');
            });
    }

    /**
     * Scope to filter parents by last name.
     *
     * @param  Builder  $query
     * @param  string  $lastname
     * @return Builder
     */
    public function scopeByLastNameParent($query, $lastname)
    {
        $query->where('usergroup_id', self::PARENT_USERGROUP_ID)->wherehas('parents', function ($query) use ($lastname) {
            $query->whereHas('userParent', function ($q) use ($lastname) {
                $q->whereHas('userprofile', function ($qu) use ($lastname) {
                    $qu->where('lastname', 'LIKE', $lastname.'%');
                });
            });
        });

        return $query;
    }

    /**
     * Scope to filter parents by mobile number.
     *
     * @param  Builder  $query
     * @param  string  $mobile_no
     * @return Builder
     */
    public function scopeByMobileNoParent($query, $mobile_no)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->where('mobile_no', 'LIKE', $mobile_no.'%');
    }

    /**
     * Scope to filter parents by email.
     *
     * @param  Builder  $query
     * @param  string  $email
     * @return Builder
     */
    public function scopeByEmailParent($query, $email)
    {
        $query->where('usergroup_id', self::PARENT_USERGROUP_ID)->wherehas('parents', function ($query) use ($email) {
            $query->whereHas('userParent', function ($q) use ($email) {
                $q->where('email', $email);
            });
        });

        return $query;
    }

    /**
     * Scope to filter parents by qualification.
     *
     * @param  Builder  $query
     * @param  int  $qualification
     * @return Builder
     */
    public function scopeByQualificationParent($query, $qualification)
    {
        $query->where('usergroup_id', self::PARENT_USERGROUP_ID)->wherehas('parents', function ($query) use ($qualification) {
            $query->whereHas('userParent', function ($q) use ($qualification) {
                $q->whereHas('parentprofile', function ($qu) use ($qualification) {
                    $qu->where('qualification_id', '=', $qualification);
                });
            });
        });

        return $query;
    }

    /**
     * Scope to filter parents by occupation.
     *
     * @param  Builder  $query
     * @param  string  $occupation
     * @return Builder
     */
    public function scopeByOccupationParent($query, $occupation)
    {
        $query->where('usergroup_id', self::PARENT_USERGROUP_ID)->wherehas('parents', function ($query) use ($occupation) {
            $query->whereHas('userParent', function ($q) use ($occupation) {
                $q->whereHas('parentprofile', function ($qu) use ($occupation) {
                    $qu->where('profession', 'LIKE', $occupation);
                });
            });
        });

        return $query;
    }

    /**
     * Scope to filter parents by student's standard/grade.
     *
     * @param  Builder  $query
     * @param  int  $standardlink_id
     * @return Builder
     */
    public function scopeByStandardLinkParent($query, $standardlink_id)
    {
        $query->where('usergroup_id', self::PARENT_USERGROUP_ID)->wherehas('parents', function ($query) use ($standardlink_id) {
            $query->whereHas('userParent', function ($quer) use ($standardlink_id) {
                $quer->whereHas('children', function ($que) use ($standardlink_id) {
                    $que->whereHas('userStudent', function ($qu) use ($standardlink_id) {
                        $qu->whereHas('studentAcademicLatest', function ($q) use ($standardlink_id) {
                            $q->where('standardlink_id', $standardlink_id);
                        });
                    });
                });
            });
        });

        return $query;
    }

    /**
     * Scope to filter parents by student's standard/grade (list variant).
     *
     * @param  Builder  $query
     * @param  int  $standardlink_id
     * @return Builder
     */
    public function scopeByStandardLinkParentList($query, $standardlink_id)
    {
        $query->whereHas('children', function ($que) use ($standardlink_id) {
            $que->whereHas('userStudent', function ($qu) use ($standardlink_id) {
                $qu->whereHas('studentAcademicLatest', function ($q) use ($standardlink_id) {
                    $q->where('standardlink_id', $standardlink_id);
                });
            });
        });

        return $query;
    }

    /**
     * Scope to filter parents by student name.
     *
     * @param  Builder  $query
     * @param  string  $student_name
     * @return Builder
     */
    public function scopeByStudentNameParent($query, $student_name)
    {
        return $query->where('usergroup_id', self::PARENT_USERGROUP_ID)
            ->whereHas('children', function ($que) use ($student_name) {
                $que->whereHas('userStudent', function ($qu) use ($student_name) {
                    $qu->whereHas('userprofile', function ($q) use ($student_name) {
                        $q->where('firstname', 'LIKE', $student_name.'%');
                    });
                });
            });
    }

    /**
    /**
     * Get teacher details for this user.
     *
     * Backward compatibility method that delegates to TeacherUser.
     *
     * @return array
     */
    public function getTeacherDetails()
    {
        if ($this->isTeacher() && method_exists($this, 'getTeacherDetails')) {
            return parent::getTeacherDetails();
        }

        return [];
    }

    /**
     * Get parent details for this user.
     *
     * Backward compatibility method that delegates to ParentUser.
     *
     * @return array
     */
    public function getParentDetails()
    {
        if ($this->isParent() && method_exists($this, 'getParentDetails')) {
            return parent::getParentDetails();
        }

        return [];
    }

    /**
     * Get children names in formatted string.
     *
     * Backward compatibility method that delegates to StudentUser.
     *
     * @return string
     */
    public function getChildren()
    {
        if (method_exists($this, 'getChildren')) {
            return parent::getChildren();
        }

        return '';
    }

    /**
     * Get alumni education details.
     *
     * Backward compatibility method that delegates to AlumniUser.
     *
     * @return array
     */
    public function getAlumniEducationDetails()
    {
        if ($this->isAlumni() && method_exists($this, 'getAlumniEducationDetails')) {
            return parent::getAlumniEducationDetails();
        }

        return [];
    }

    /**
     * Get alumni education details formatted.
     *
     * Backward compatibility method that delegates to AlumniUser.
     *
     * @return array
     */
    public function getAlumniEducation()
    {
        if ($this->isAlumni() && method_exists($this, 'getAlumniEducation')) {
            return parent::getAlumniEducation();
        }

        return [];
    }

    /**
     * Get alumni work experience details.
     *
     * Backward compatibility method that delegates to AlumniUser.
     *
     * @return array
     */
    public function getAlumniWorkDetails()
    {
        if ($this->isAlumni() && method_exists($this, 'getAlumniWorkDetails')) {
            return parent::getAlumniWorkDetails();
        }

        return [];
    }

    /**
     * Get alumni work experience details formatted.
     *
     * Backward compatibility method that delegates to AlumniUser.
     *
     * @return array
     */
    public function getAlumniWork()
    {
        if ($this->isAlumni() && method_exists($this, 'getAlumniWork')) {
            return parent::getAlumniWork();
        }

        return [];
    }

    /**
     * Get promotion records for this user.
     *
     * @return HasMany
     */
    public function promotion()
    {
        return $this->hasMany('App\Models\Promotion', 'user_id', 'id');
    }

    /**
     * Get discipline records for this user.
     *
     * @return HasMany
     */
    public function disciplineUser()
    {
        return $this->hasMany('App\Models\Discipline', 'user_id', 'id');
    }

    /**
     * Get discipline records reported by this user.
     *
     * @return HasMany
     */
    public function disciplineTeacher()
    {
        return $this->hasMany('App\Models\Discipline', 'reported_by', 'id');
    }

    /**
     * Get the full name of this user.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->userprofile->firstname.' '.$this->userprofile->lastname;
    }

    /**
     * Get the standard link for this class teacher.
     *
     * @return HasOne
     */
    public function standardLink()
    {
        return $this->hasOne('App\Models\StandardLink', 'class_teacher_id', 'id');
    }

    /**
     * Get teacher links for this teacher.
     *
     * @return HasMany
     */
    public function teacherlink()
    {
        return $this->hasMany('\App\Models\Teacherlink', 'teacher_id', 'id');
    }

    /**
     * Get teacher links for current academic year.
     *
     * @return HasMany
     */
    public function teacherlinkCurrentAcademicYear()
    {
        $academic_year = SiteHelper::getAcademicYear($this->school_id);

        return $this->hasMany('\App\Models\Teacherlink', 'teacher_id', 'id')->where('academic_year_id', $academic_year->id);
    }

    /**
     * Get attendance records for this user.
     *
     * @return HasMany
     */
    public function attendanceUser()
    {
        return $this->hasMany('\App\Models\Attendance', 'user_id', 'id');
    }

    /**
     * Get absent attendance records for current academic year.
     *
     * @return Collection
     */
    public function getAttendanceUserAbsentAttribute()
    {
        $academic_year = SiteHelper::getAcademicYear($this->school_id);

        return $this->attendanceUser->where('academic_year_id', $academic_year->id)->where('status', 0);
    }

    /**
     * Get attendance records recorded by this user.
     *
     * @return HasMany
     */
    public function attendanceAdmin()
    {
        return $this->hasMany('\App\Models\Attendance', 'recorded_by', 'id');
    }

    /**
     * Get book lending for this user.
     *
     * @return BelongsTo
     */
    public function booklending()
    {
        return $this->belongsTo('\App\Models\BookLending', 'user_id');
    }

    /**
     * Get book lending records for this user.
     *
     * @return HasMany
     */
    public function lending()
    {
        return $this->hasMany('\App\Models\BookLending', 'user_id', 'id');
    }

    /**
     * Get assignments created by this teacher.
     *
     * @return HasMany
     */
    public function assignment()
    {
        return $this->hasMany('\App\Models\Assignment', 'teacher_id', 'id');
    }

    /**
     * Get student assignments for this student.
     *
     * @return HasMany
     */
    public function studentAssignment()
    {
        return $this->hasMany('\App\Models\StudentAssignment', 'user_id', 'id');
    }

    /**
     * Get student assignments marked by this teacher.
     *
     * @return HasMany
     */
    public function teacherAssignment()
    {
        return $this->hasMany('\App\Models\StudentAssignment', 'marks_given_by', 'id');
    }

    /**
     * Get leave applications requested by this user.
     *
     * @return HasMany
     */
    public function requestedUser()
    {
        return $this->hasMany('\App\Models\TeacherLeaveApplication', 'user_id', 'id');
    }

    /**
     * Get leave applications approved by this user.
     *
     * @return HasMany
     */
    public function approvedUser()
    {
        return $this->hasMany('\App\Models\TeacherLeaveApplication', 'approved_by', 'id');
    }

    /**
     * Get fee payments for this user.
     *
     * @return HasMany
     */
    public function feePayment()
    {
        if (class_exists('Gegok12\Fee\Models\FeePayment')) {
            return $this->hasMany('\Gegok12\Fee\Models\FeePayment', 'user_id', 'id');
        } else {
            return $this->hasMany('\App\Models\FeePayment', 'user_id', 'id');
        }
    }

    /**
     * Get the school logo for this user's school.
     *
     * @return SchoolDetail
     */
    public function getSchoolLogoAttribute()
    {
        return $this->school->schoolDetailLogo;
    }

    /**
     * Get the school logo path for this user's school.
     *
     * @return string
     */
    public function getSchoolLogoPathAttribute()
    {
        return $this->school->schoolDetailLogo->LogoPath;
    }

    /**
     * Get messages sent by this user.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany('\App\Models\Chat');
    }

    /**
     * Check if this user is in a conversation.
     *
     * @param  int  $id
     * @return bool
     */
    public function inConversation($id)
    {
        return $this->conversations->contains('id', $id);
    }

    /**
     * Check if this user has read a conversation.
     *
     * @return \DateTime|null
     */
    public function hasRead(Conversation $conversation)
    {
        return $this->conversations->find($conversation->id)->pivot->read_at;

    }

    /**
     * Get the presenter for this user.
     *
     * @return UserPresenter
     */
    public function present()
    {
        return new UserPresenter($this);
    }

    /**
     * Get conversations for this user.
     *
     * @return BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany('App\Models\Conversation')->withPivot('read_at');
    }

    /**
     * Get count of approved leave applications.
     *
     * @return int
     */
    public function getLeaveCountAttribute()
    {
        return $this->requestedUser->where('status', 'approved')->count();
    }

    /**
     * Get documents for this user.
     *
     * @return HasMany
     */
    public function document()
    {
        return $this->hasMany('\App\Models\Document', 'user_id', 'id');
    }

    /**
     * Get authentication records for this user.
     *
     * @return HasMany
     */
    public function authentication()
    {
        return $this->hasMany('\App\Models\Authentication', 'user_id', 'id');
    }

    /**
     * Get task for this user.
     *
     * @return BelongsTo
     */
    public function task()
    {
        return $this->belongsTo('\App\Models\Task', 'user_id');
    }

    /**
     * Get approved lesson plans by this user.
     *
     * @return HasMany
     */
    public function approvedLessonPlan()
    {
        return $this->hasMany('\App\Models\LessonPlanApproval', 'approved_by', 'id');
    }

    /**
     * Get post details created by this user.
     *
     * @return HasMany
     */
    public function postDetail()
    {
        return $this->hasMany('\App\Models\PostDetail', 'user_id', 'id');
    }

    /**
     * Get post comments by this user.
     *
     * @return HasMany
     */
    public function postComment()
    {
        return $this->hasMany('\App\Models\PostComment', 'user_id', 'id');
    }

    /**
     * Get post comment details by this user.
     *
     * @return HasMany
     */
    public function postCommentDetail()
    {
        return $this->hasMany('\App\Models\PostCommentDetail', 'user_id', 'id');
    }

    /**
     * Get visitor log for this user as employee.
     *
     * @return BelongsTo
     */
    public function visitorlog()
    {
        return $this->belongsTo('\App\Models\VisitorLog', 'employee_id');
    }

    /**
     * Get visitor log for this user as student.
     *
     * @return BelongsTo
     */
    public function visitor()
    {
        return $this->belongsTo('\App\Models\VisitorLog', 'student_id');
    }

    /**
     * Get bank details for this user.
     *
     * @return HasOne
     */
    public function bankdetail()
    {
        return $this->hasOne('App\Models\TransactionAccount', 'user_id', 'id');
    }

    /**
     * Get payroll records for this user.
     *
     * @return HasMany
     */
    public function payrolls()
    {
        return $this->hasMany('App\Models\Payroll', 'staff_id', 'id');
    }

    /**
     * Get payroll transactions for this user.
     *
     * @return HasMany
     */
    public function payrolltransactions()
    {
        return $this->hasMany('App\Models\PayrollTransaction', 'staff_id', 'id');
    }

    /**
     * Get salary details for this user.
     *
     * @return array|int
     */
    public function salarydetail()
    {
        $array = [];
        if (count($this->payrolltransactions) != 0) {

            $array['salaryadvance'] = $this->payrolltransactions()->where('paytype_id', self::PAYROLL_TYPE_SALARY_ADVANCE)->sum('amount');
            $array['returnadvance'] = $this->payrolltransactions()->where('paytype_id', self::PAYROLL_TYPE_RETURN_ADVANCE)->sum('amount');
            $array['pending'] = $array['salaryadvance'] - $array['returnadvance'];

            return $array;

        }

        return 0;
    }

    /**
     * Scope to filter users with transport driver role.
     *
     * @param  Builder  $query
     * @param  int  $usergroup_id
     * @return Builder
     */
    public static function scopeByDriver($query, $usergroup_id)
    {
        return $query->whereHas(
            'roles', function ($q) {
                $q->where('name', 'transport_driver');
            }
        );
    }

    /**
     * Get route student record for this user.
     *
     * @return HasOne
     */
    public function routeStudent()
    {
        return $this->hasOne('App\Models\RouteStudent', 'user_id', 'id');
    }

    /**
     * Get the FCM notification token for this user.
     *
     * @param  mixed  $notification
     * @return string
     */
    public function routeNotificationForFcm($notification)
    {

        // return $notification->data['token'];
        return $notification->token;
        // return $this->userdevicetoken()->pluck('token')->toArray();
    }

    /**
     * Scope to filter active users.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeByActive($query)
    {
        $query->where('status', 'active');

        return $query;
    }

    /**
     * Get the latest teacher profile for this user.
     *
     * @return HasOne
     */
    public function latestTeacherProfile()
    {
        return $this->hasOne(TeacherProfile::class, 'user_id', 'id')->latestOfMany();
    }

    /**
     * Get the most recent successful login activity-log entry for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function lastLogin()
    {
        return $this->morphOne(\Spatie\Activitylog\Models\Activity::class, 'subject')
            ->where('log_name', 'login')
            ->latestOfMany();
    }

    /**
     * Scope to filter users by employee ID.
     *
     * @param  Builder  $query
     * @param  string  $employeeId
     * @return Builder
     */
    public function scopeByEmployeeId($query, $employeeId)
    {
        return $query->whereHas('latestTeacherProfile', function ($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        });
    }

    // ===== FACTORY METHODS FOR SPECIALIZED USER CLASSES =====
    /**
     * Get the appropriate specialized user instance based on usergroup_id.
     * Returns specialized class (TeacherUser, StudentUser, etc.) or self.
     *
     * @return User|TeacherUser|StudentUser|ParentUser|AlumniUser|AdminUser|LibrarianUser|AccountantUser
     */
    public function getSpecializedInstance()
    {
        return match ($this->usergroup_id) {
            self::TEACHER_USERGROUP_ID => new TeacherUser($this->getAttributes()),
            self::STUDENT_USERGROUP_ID => new StudentUser($this->getAttributes()),
            self::PARENT_USERGROUP_ID => new ParentUser($this->getAttributes()),
            self::ALUMNI_USERGROUP_ID => new AlumniUser($this->getAttributes()),
            self::SCHOOLADMIN_USERGROUP_ID, self::SCHOOLSUBADMIN_USERGROUP_ID => new AdminUser($this->getAttributes()),
            self::LIBRARIAN_USERGROUP_ID => new LibrarianUser($this->getAttributes()),
            self::ACCOUNTANT_USERGROUP_ID => new AccountantUser($this->getAttributes()),
            default => $this,
        };
    }

    // ===== TYPE CHECKING METHODS =====
    /**
     * Check if this user is a teacher.
     */
    public function isTeacher(): bool
    {
        return (int) $this->usergroup_id === (int) self::TEACHER_USERGROUP_ID;
    }

    /**
     * Check if this user is a student.
     */
    public function isStudent(): bool
    {
        return (int) $this->usergroup_id === (int) self::STUDENT_USERGROUP_ID;
    }

    /**
     * Check if this user is a parent.
     */
    public function isParent(): bool
    {
        return (int) $this->usergroup_id === (int) self::PARENT_USERGROUP_ID;
    }

    /**
     * Check if this user is alumni/old student.
     */
    public function isAlumni(): bool
    {
        return (int) $this->usergroup_id === (int) self::ALUMNI_USERGROUP_ID;
    }

    /**
     * Check if this user is a school admin.
     */
    public function isAdmin(): bool
    {
        return in_array((int) $this->usergroup_id, [
            (int) self::SCHOOLADMIN_USERGROUP_ID,
            (int) self::SCHOOLSUBADMIN_USERGROUP_ID,
        ]);
    }

    /**
     * Check if this user is a librarian.
     */
    public function isLibrarian(): bool
    {
        return (int) $this->usergroup_id === (int) self::LIBRARIAN_USERGROUP_ID;
    }

    /**
     * Check if this user is an accountant.
     */
    public function isAccountant(): bool
    {
        return (int) $this->usergroup_id === (int) self::ACCOUNTANT_USERGROUP_ID;
    }

    /**
     * Check if this user is a receptionist.
     */
    public function isReceptionist(): bool
    {
        return (int) $this->usergroup_id === (int) self::RECEPTIONIST_USERGROUP_ID;
    }

    /**
     * Check if this user is a stock keeper.
     */
    public function isStockKeeper(): bool
    {
        return (int) $this->usergroup_id === (int) self::STOCK_KEEPER_USERGROUP_ID;
    }

    /**
     * Check if this user is non-teaching staff.
     */
    public function isNonTeachingStaff(): bool
    {
        return (int) $this->usergroup_id === (int) self::NON_TEACHING_USERGROUP_ID;
    }

    /**
     * Check if this user is staff (any staff member except student/parent/admin).
     */
    public function isStaff(): bool
    {
        return in_array($this->usergroup_id, [
            self::TEACHER_USERGROUP_ID,
            self::LIBRARIAN_USERGROUP_ID,
            self::RECEPTIONIST_USERGROUP_ID,
            self::ACCOUNTANT_USERGROUP_ID,
            self::STOCK_KEEPER_USERGROUP_ID,
            self::NON_TEACHING_USERGROUP_ID,
        ]);
    }
}
