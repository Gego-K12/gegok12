<?php

namespace App\Helpers;

use App\Http\Resources\AssignmentTeacher as AssignmentTeacherResource;
use App\Http\Resources\API\NonScholastic as NonScholasticResource;
use App\Http\Resources\TeacherDetail as TeacherDetailResource;
use App\Http\Resources\StandardLink as StandardLinkResource;
use App\Http\Resources\API\Scholastic as ScholasticResource;
use App\Http\Resources\TeacherLink as TeacherLinkResource;
use App\Http\Resources\API\Country as CountryResource;
use App\Http\Resources\Standard as StandardResource;
use App\Http\Resources\API\State as StateResource;
use App\Http\Resources\API\City as CityResource;
use App\Models\TeacherLeaveApplication;
use Illuminate\Support\Facades\Cache;
use App\Models\Users\TeacherUser;
use App\Models\Users\StudentUser;
use App\Models\TeacherProfile;
use App\Models\Qualification;
use App\Models\NonScholastic;
use App\Models\StandardLink;
use App\Models\AcademicYear;
use App\Models\Teacherlink;
use App\Models\Scholastic;
use App\Models\Standard;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User;
/**
 * Class SiteHelper
 *
 * Central helper class providing cached lookup data
 * and reusable business logic across the application.
 *
 * Responsibilities include:
 * - Academic year resolution
 * - Location data (Country, State, City)
 * - Teacher & student related utilities
 * - Static dropdown lists
 * - Cached master data access
 *
 * All methods are static and cache-aware.
 *
 * @package App\Helpers
 */

class SiteHelper
{
    /**
     * Get the current academic year for a school.
     *
     * @param int|string $school_id
     * @return \App\Models\AcademicYear|null
     */

    public static function getAcademicYear($school_id)
    {
        $schoolCacheKey = "academic_year_for_school_" . $school_id;
        return Cache::remember($schoolCacheKey, env('CACHE_TIME'), function () use ($school_id) {
            $academic_year = AcademicYear::where([['school_id', $school_id]]);   //['status',1]
            if (Cache::has('academic_year') && Cache::get('academic_year') != '') {
                $academic_year_id = Cache::get('academic_year');
                $academic_year = $academic_year->where('id', $academic_year_id);
            }

            $academic_year = $academic_year->first();
            return $academic_year;
        });
    }
    /**
     * Get the administrator user for a given school.
     *
     * The result is cached to improve performance.
     * The administrator is resolved based on the
     * school ID and user role.
     *
     * @param int|string $school_id School identifier
     * @return \App\Models\User|null
     */
    public static function getAdmin($school_id)
    {
        $schoolCacheKey = "admin" . $school_id;
        return Cache::remember($schoolCacheKey, env('CACHE_TIME'), function () use ($school_id) {
            $academic_year = SiteHelper::getAcademicYear($school_id);
            //return User::where([['school_id',$school_id],['academic_year_id',$academic_year->id]])->ByRole(3)->first();
            return User::where('school_id', $school_id)->ByRole(3)->first();
        });
    }
     /**
     * Get the list of active countries.
     *
     * The countries are cached to improve performance
     * and returned as a resource collection keyed by ID.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getCountries()
    {
        return Cache::remember("countries", env('CACHE_TIME'), function () {
            $country = Country::where('status', '1')->get();
            return CountryResource::collection($country)->keyby('id');
        });
    }
    /**
     * Get the list of states grouped by country.
     *
     * The states are cached and returned as a
     * resource collection grouped by `country_id`.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getStates()
    {
        return Cache::remember("states", env('CACHE_TIME'), function () {
            $state = State::get();
            return StateResource::collection($state)->groupby('country_id');
        });
    }
    /**
     * Get the list of cities grouped by state.
     *
     * The cities are cached and returned as a
     * resource collection grouped by `state_id`.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getCities()
    {
        return Cache::remember("cities", env('CACHE_TIME'), function () {
            $city  = City::get();
            return CityResource::collection($city)->groupby('state_id');
        });
    }
     /**
     * Get the list of qualifications.
     *
     * Returns active qualifications of types:
     * - others
     * - pg
     * - ug
     *
     * Results are cached for performance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getQualifications()
    {
        return Cache::remember("qualifications", env('CACHE_TIME'), function () {
            return Qualification::where([['status', 1], ['type', 'others']])->orWhere('type', 'pg')->orWhere('type', 'ug')->orderBy('id', 'DESC')->get();
        });
    }
    /**
     * Get the list of additional certificates.
     *
     * Returns active qualifications of types:
     * - teacher
     * - others
     *
     * Results are cached for performance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAdditionalCertificates()
    {
        return Cache::remember("additionalcertificates", env('CACHE_TIME'), function () {
            return Qualification::where([['status', 1], ['type', 'teacher']])->orWhere('type', 'others')->orderBy('id', 'DESC')->get();
        });
    }
    /**
     * Get the list of undergraduate (UG) qualifications.
     *
     * Returns active qualifications of type `ug`.
     * Results are cached for performance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUGList()
    {
        return Cache::remember("UGList", env('CACHE_TIME'), function () {
            return Qualification::where([['status', 1], ['type', 'ug']])->get();
        });
    }
    /**
     * Get the list of postgraduate (PG) qualifications.
     *
     * Returns active qualifications of type `pg`.
     * Results are cached for better performance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPGList()
    {
        return Cache::remember("PGList", env('CACHE_TIME'), function () {
            return Qualification::where([['status', 1], ['type', 'pg']])->get();
        });
    }
    /**
     * Get the list of standard links for a school.
     *
     * Fetches standards and sections mapped for the
     * current academic year and returns them as a
     * resource collection ordered by standard and section.
     *
     * Results are cached per school.
     *
     * @param int|string $school_id School identifier
     * @return \Illuminate\Support\Collection|null
     */
    public static function getStandardLinkList($school_id)
    {
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $standardLinkCacheKey = 'standardLink' . $school_id;
        return Cache::remember($standardLinkCacheKey, env('CACHE_TIME'), function () use ($school_id, $academic_year) {
            $standards = Standard::where('school_id', $school_id)->orderBy('order')->pluck('id')->toArray();
            if (count($standards) > 0) {
                $standard = implode(' ,', $standards);
                $standardLink = StandardLink::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]])->orderByRaw('FIELD(standard_id,' . $standard . ')')->orderBy('section_id')->groupBy(['standard_id', 'section_id'])->get();
                return StandardLinkResource::collection($standardLink);
            }
        });
    }
    /**
     * Get the list of standards for a school.
     *
     * Returns standards ordered by display order
     * and wrapped in a resource collection.
     *
     * Results are cached per school.
     *
     * @param int|string $school_id School identifier
     * @return \Illuminate\Support\Collection|null
     */
    public static function getStandardList($school_id)
    {
        $standardCacheKey = 'standard_' . $school_id;
        return Cache::remember($standardCacheKey, env('CACHE_TIME'), function () use ($school_id) {
            $standards = Standard::where('school_id', $school_id)->orderBy('order')->get();
            if (count($standards) > 0) {
                return StandardResource::collection($standards);
            }
        });
    }
    /**
     * Get the list of teaching staff designations.
     *
     * Returns a cached list of predefined teaching
     * designations with identifier and display name.
     *
     * @return array
     */
    public static function getTeachingDesignations()
    {
        return Cache::remember("teaching_designations", env('CACHE_TIME'), function () {
            $name = array('Principal', 'Vice Principal', 'Head Of The Department', 'Senior Teacher', 'Assistant Teacher', 'Teacher', 'Co-ordinator', 'Physical Education Teacher', 'Others');
            $id = array('principal', 'vice_principal', 'head_of_the_department', 'senior_teacher', 'assistant_teacher', 'teacher', 'co_ordinator', 'physical_education_teacher', 'others');
            for ($i = 1; $i <= count($name); $i++) {
                $array[$i]['id']    = $id[$i - 1];
                $array[$i]['name']  = $name[$i - 1];
            }

            return $array;
        });
    }
    /**
     * Get the list of non-teaching staff designations.
     *
     * Returns a cached list of predefined non-teaching
     * designations with identifier and display name.
     *
     * @return array
     */
    public static function getNonTeachingDesignations()
    {
        return Cache::remember("non_teaching_designations", env('CACHE_TIME'), function () {
            $name = array('Accountant', 'Receptionist', 'Librarian', 'Lab Assistant', 'Clerk', 'Stock Keeper', 'Peon', 'Driver', 'Helpers', 'Security', 'Transport Coordinator', 'Others');
            $id = array('accountant', 'receptionist', 'librarian', 'lab_assistant', 'clerk', 'stock_keeper', 'peon', 'driver', 'helpers', 'security', 'transport_coordinator', 'others');


            for ($i = 1; $i <= count($name); $i++) {
                $array[$i]['id']    = $id[$i - 1];
                $array[$i]['name']  = $name[$i - 1];
            }

            return $array;
        });
    }
    /**
     * Get the list of blood groups.
     *
     * Returns a cached list of blood groups with
     * identifier and display name.
     *
     * @return array
     */
    public static function getBloodGroups()
    {
        $array = [];

        $list_id = array('a+', 'a1+', 'b+', 'b1+', 'o+', 'ab+', 'a1b+', 'a-', 'a1-', 'b-', 'b1-', 'o-', 'ab-', 'a1b-');
        $list_name = array('A+', 'A1+', 'B+', 'B1+', 'O+', 'AB+', 'A1B+', 'A-', 'A1-', 'B-', 'B1-', 'O-', 'AB-', 'A1B-');

        return Cache::remember("blood_groups", env('CACHE_TIME'), function () use ($list_id, $list_name) {
            for ($i = 1; $i <= count($list_name); $i++) {
                $array[$i]['num'] = $list_id[$i - 1];
                $array[$i]['name'] = $list_name[$i - 1];
            }
            return $array;
        });
    }
    /**
     * Get the list of marital statuses.
     *
     * Returns a cached list of marital status options
     * with identifier and display name.
     *
     * @return array
     */
    public static function getMaritalList()
    {
        $array = [];

        $list_id = array('single', 'married', 'divorced', 'widowed');
        $list_name = array('Single', 'Married', 'Divorced', 'Widowed');

        return Cache::remember("marital_list", env('CACHE_TIME'), function () use ($list_id, $list_name) {

            for ($i = 1; $i <= count($list_name); $i++) {
                $array[$i]['id'] = $list_id[$i - 1];
                $array[$i]['name'] = $list_name[$i - 1];
            }
            return $array;
        });
    }
    /**
     * Get the list of caste categories.
     *
     * Returns a cached list of caste options
     * with identifier and display name.
     *
     * @return array
     */
    public static function getCasteList()
    {
        $array = [];

        $list_id = array('BC', 'BCM', 'FC', 'MBC', 'OBC', 'Others', 'SC', 'SCA', 'ST');
        $list_name = array('BC', 'BCM', 'FC', 'MBC', 'OBC', 'Others', 'SC', 'SCA', 'ST');


        return Cache::remember("caste_list", env('CACHE_TIME'), function () use ($list_id, $list_name) {

            for ($i = 1; $i <= count($list_name); $i++) {
                $array[$i]['id'] = $list_id[$i - 1];
                $array[$i]['name'] = $list_name[$i - 1];
            }
            return $array;
        });
    }
    /**
     * Get the list of transport modes.
     *
     * Returns a cached list of transport options
     * with identifier and display name.
     *
     * @return array
     */
    public static function getTransportList()
    {
        $array = [];


        $list_id = array('auto', 'car', 'city_bus', 'cycle', 'rickshaw', 'school_bus', 'taxi', 'walking');
        $list_name = array('Auto', 'Car', 'City Bus', 'Cycle', 'Rickshaw', 'School Bus', 'Taxi', 'Walking');

        return Cache::remember("transport_list", env('CACHE_TIME'), function () use ($list_id, $list_name) {

            for ($i = 1; $i <= count($list_name); $i++) {
                $array[$i]['id'] = $list_id[$i - 1];
                $array[$i]['name'] = $list_name[$i - 1];
            }
            return $array;
        });
    }
    /**
     * Get the total number of active students in a class.
     *
     * Counts students based on:
     * - School
     * - Academic year
     * - Standard & section (standardLink)
     *
     * Result is cached per class for performance.
     *
     * @param int|string $school_id School identifier
     * @param int|string $academic_year_id Academic year identifier
     * @param int|string $standardLink_id Standard-link identifier
     * @return int
     */
    public static function getClassStudentCount($school_id, $academic_year_id, $standardLink_id)
    {
        $key = "class_student_count" . $standardLink_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year_id, $standardLink_id) {
            return StudentUser::where([['status', 'active']])->ByRole(6)->whereHas('studentAcademic', function ($query) use ($school_id, $academic_year_id, $standardLink_id) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year_id],
                    ['standardLink_id', $standardLink_id]
                ]);
            })->count();
        });
    }
    /**
     * Get the list of Heads of Department (HOD) for a school.
     *
     * Filters teachers by:
     * - Active status
     * - Head of the Department designation
     * - Current academic year
     *
     * Results are cached per school and academic year.
     *
     * @param int|string $school_id School identifier
     * @return \Illuminate\Support\Collection
     */
    public static function getHODList($school_id)
    {
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $key = "hod_list_" . $school_id . '_' . $academic_year->id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year) {
            $users = TeacherUser::ByRole(5)->where('status', 'active')->whereHas('teacherprofile', function ($query) use ($school_id, $academic_year) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year->id],
                    ['designation', 'head_of_the_department']
                ]);
            })->get();
            $teachers = TeacherDetailResource::collection($users);
            return $teachers;
        });
    }
    /**
     * Get the list of principals and vice principals for a school.
     *
     * Filters active teaching staff with designation:
     * - principal
     * - vice_principal
     *
     * Results are cached per school and academic year.
     *
     * @param int|string $school_id School identifier
     * @return \Illuminate\Support\Collection
     */
    public static function getPrincipalList($school_id)
    {
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $key = "principal_list_" . $school_id . '_' . $academic_year->id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year) {
            $users = TeacherUser::ByRole(5)->where('status', 'active')->whereHas('teacherprofile', function ($query) use ($school_id, $academic_year) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year->id],
                    ['designation', 'principal']
                ])->orWhere('designation', 'vice_principal');
            })->get();
            $teachers = TeacherDetailResource::collection($users);
            return $teachers;
        });
    }
    /**
     * Get the list of active teaching staff for a school.
     *
     * Filters users by:
     * - Teaching user group
     * - Active status
     * - Academic year
     *
     * Results are cached per school and academic year.
     *
     * @param int|string $school_id School identifier
     * @param int|string $academic_year_id Academic year identifier
     * @return \Illuminate\Support\Collection
     */
    public static function getTeachingStaffList($school_id, $academic_year_id)
    {
        $key = "teaching_staff_lists_" . $school_id . '_' . $academic_year_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year_id) {
            return TeacherUser::where('usergroup_id', 5)->where('status', 'active')->whereHas('teacherprofile', function ($query) use ($school_id, $academic_year_id) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year_id]
                ]);
            })->get()->sortBy('userprofile.firstname');
        });
    }

    /**
     * Get standard and subject list assigned to a teacher.
     *
     * This method returns:
     * - Standards where the teacher is a class teacher
     * - Standards where the teacher is assigned as a subject teacher
     * - Subjects grouped by standard link
     *
     * Data is resolved for the current academic year.
     *
     * @param int|string $school_id School identifier
     * @param int|string $teacher_id Teacher identifier
     * @return array{
     *     standardLinklist: \Illuminate\Support\Collection,
     *     subjectlist: \Illuminate\Support\Collection
     * }
     */
    public static function getStandardSubjectList($school_id, $teacher_id)
    {
        $academic_year = SiteHelper::getAcademicYear($school_id);
        //$key = "standard_subject_list_".$school_id.'_'.$academic_year->id.'_'.$teacher_id;
        //return Cache::remember( $key, env('CACHE_TIME'), function () use ($school_id,$academic_year,$teacher_id) {

        $standardLinks = StandardLink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['class_teacher_id', $teacher_id]
        ])->pluck('id')->toArray();

        $teacherlinks = Teacherlink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['teacher_id', $teacher_id]
        ])->pluck('standardLink_id')->toArray();

        $standards = array_merge($standardLinks, $teacherlinks);

        $standardLink = StandardLink::whereIn('id', $standards)->get();

        $standards = StandardLinkResource::collection($standardLink);

        $teacherLink = Teacherlink::where([
            ['school_id', $school_id],
            ['academic_year_id', $academic_year->id],
            ['teacher_id', $teacher_id]
        ])->get();
        //$standardLinklist = AssignmentTeacherResource::collection($teacherLink);
        $subjectlist = TeacherLinkResource::collection($teacherLink)->groupBy('standardLink_id');

        $array = [];

        $array['standardLinklist'] = $standards;
        $array['subjectlist'] = $subjectlist;
        return $array;
        // });
    }
    /**
     * Get the list of feedback categories.
     *
     * Returns a cached list of predefined feedback
     * categories used for feedback and support modules.
     *
     * @return array
     */
    public static function getFeedbackCategoryList()
    {
        $array = [];

        $list_id = array('feedback_or_bug_for_app_or_software', 'student_profile_or_info', 'complaints', 'suggestions', 'others');
        $list_name = array('Feedback / Bug For App / Software', 'Student Profile / Info', 'Complaints', 'Suggestions', 'Others');

        return Cache::remember("feedback_category_list", env('CACHE_TIME'), function () use ($list_id, $list_name) {

            for ($i = 1; $i <= count($list_name); $i++) {
                $array[$i]['id'] = $list_id[$i - 1];
                $array[$i]['name'] = $list_name[$i - 1];
            }
            return $array;
        });
    }
    /**
     * Get the list of students for a specific class.
     *
     * Fetches active students based on:
     * - School
     * - Academic year
     * - Standard & section (standardLink)
     *
     * Results are cached per class and sorted
     * by student's first name.
     *
     * @param int|string $school_id School identifier
     * @param int|string $academic_year_id Academic year identifier
     * @param int|string $standardLink_id Standard-link identifier
     * @return \Illuminate\Support\Collection
     */
    public static function getClassStudents($school_id, $academic_year_id, $standardLink_id)
    {
        //dd($standardLink_id);

        $key = "class_students_" . $standardLink_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year_id, $standardLink_id) {
            return StudentUser::ByRole(6)->where('status', 'active')->whereHas('studentAcademic', function ($query) use ($school_id, $academic_year_id, $standardLink_id) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year_id],
                    ['standardLink_id', $standardLink_id]
                ]);
            })->get()->sortBy('userprofile.firstname');
        });
    }
    /**
     * Get the list of teachers for a school and academic year.
     *
     * Fetches active teaching staff across multiple
     * user groups and sorts them by first name.
     *
     * Results are cached per school and academic year.
     *
     * @param int|string $school_id School identifier
     * @param int|string $academic_year_id Academic year identifier
     * @return \Illuminate\Support\Collection
     */
    public static function getTeachers($school_id, $academic_year_id)
    {
        $key = "teacher_lists_" . $school_id . '_' . $academic_year_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year_id) {
            return TeacherUser::whereIn('usergroup_id', [5, 8, 10, 11, 12])->where('status', 'active')->whereHas('teacherprofile', function ($query) use ($school_id, $academic_year_id) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year_id]
                ]);
            })->get()->sortBy('userprofile.firstname');
        });
    }

    public static function getDoToTeachers($school_id, $academic_year_id)
    {
        $key = "do_to_teacher_lists_" . $school_id . '_' . $academic_year_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year_id) {
            return TeacherUser::where('usergroup_id', 5)->where('status', 'active')->whereHas('teacherprofile', function ($query) use ($school_id, $academic_year_id) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year_id]
                ]);
            })->get()->sortBy('userprofile.firstname');
        });
    }
     public static function getNonTeachers($school_id, $academic_year_id)
    {
        $key = "non_teacher_lists_" . $school_id . '_' . $academic_year_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year_id) {
            return TeacherUser::whereIn('usergroup_id', [8, 10, 11, 12])->where('status', 'active')->whereHas('teacherprofile', function ($query) use ($school_id, $academic_year_id) {
                $query->where([
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year_id]
                ]);
            })->get()->sortBy('userprofile.firstname');
        });
    }
    /**
     * Get the count of pending leave applications under a teacher.
     *
     * Calculates pending leave requests submitted by teachers
     * who report to the given teacher for a specific
     * school and academic year.
     *
     * Result is cached per school, academic year, and teacher.
     *
     * @param int|string $school_id School identifier
     * @param int|string $academic_year_id Academic year identifier
     * @param int|string $teacher_id Reporting teacher identifier
     * @return int
     */
    public static function getPendingLeaveCount($school_id, $academic_year_id, $teacher_id)
    {
        $key = "pending_leave_count_" . $school_id . '_' . $academic_year_id . '_' . $teacher_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id, $academic_year_id, $teacher_id) {
            $teacherprofiles = TeacherProfile::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year_id],
                ['reporting_to', $teacher_id]
            ])->pluck('user_id')->toArray();

            $pending_count = TeacherLeaveApplication::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year_id],
                ['status', 'pending']
            ])->whereIn('user_id', $teacherprofiles)->count();

            return $pending_count;
        });
    }
    /**
     * Get the scholastic grading list for a school.
     *
     * Returns scholastic grade definitions configured
     * for the given school as a resource collection.
     *
     * Results are cached per school.
     *
     * @param int|string $school_id School identifier
     * @return \Illuminate\Support\Collection
     */
    public static function getScholasticList($school_id)
    {
        $key = "scholastic_list_" . $school_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id) {
            $sc_grade = Scholastic::where('school_id', $school_id)->get();
            return ScholasticResource::collection($sc_grade);
        });
    }
    /**
     * Get the non-scholastic grading list for a school.
     *
     * Returns non-scholastic grade definitions configured
     * for the given school as a resource collection.
     *
     * Results are cached per school.
     *
     * @param int|string $school_id School identifier
     * @return \Illuminate\Support\Collection
     */
    public static function getNonScholasticList($school_id)
    {
        $key = "non_scholastic_list_" . $school_id;
        return Cache::remember($key, env('CACHE_TIME'), function () use ($school_id) {
            $non_sc_grade = NonScholastic::where('school_id', $school_id)->get();
            return NonScholasticResource::collection($non_sc_grade);
        });
    }
    /**
     * Get the list of task assignee types.
     *
     * Returns a cached list of predefined task assignees
     * used in task or reminder modules.
     *
     * @return array
     */
    public static function getTaskAssigneeList()
    {
        $array = [];

        $list_id = array('class', 'self', 'student', 'teacher');
        $list_name = array('Class', 'Self', 'Student', 'Teachers');

        return Cache::remember("task_assignee_list", env('CACHE_TIME'), function () use ($list_id, $list_name) {
            for ($i = 0; $i < count($list_name); $i++) {
                $array[$i]['id'] = $list_id[$i];
                $array[$i]['name'] = $list_name[$i];
            }
            return $array;
        });
    }
    /**
     * Get the list of task reminder options.
     *
     * Returns a cached list of predefined reminder timings
     * used for task notifications.
     *
     * @return array
     */
    public static function getTaskReminderList()
    {
        $array = [];

        $list_id = array('one_hour_before_the_task', 'one_day_before_the_task', 'two_days_before_the_task');
        $list_name = array('One Hour Before The Task', 'One Day Before The Task', 'Two Days Before The Task');

        return Cache::remember("task_reminder_list", env('CACHE_TIME'), function () use ($list_id, $list_name) {
            for ($i = 0; $i < count($list_name); $i++) {
                $array[$i]['id'] = $list_id[$i];
                $array[$i]['name'] = $list_name[$i];
            }
            return $array;
        });
    }
    /**
     * Get the list of academic year statuses.
     *
     * Returns a cached list of academic year states
     * used across academic year management.
     *
     * @return array
     */
    public static function getAcademicYearStatusList()
    {
        $array = [];

        $list_id = array('current', 'new', 'old');
        $list_name = array('Current Academic Year', 'New Academic Year', 'Old Academic Year');

        return Cache::remember("academic_year_status_list", env('CACHE_TIME'), function () use ($list_id, $list_name) {
            for ($i = 0; $i < count($list_name); $i++) {
                $array[$i]['id'] = $list_id[$i];
                $array[$i]['name'] = $list_name[$i];
            }
            return $array;
        });
    }
    /**
     * Get class coordinators for a school.
     *
     * Returns active users (excluding the authenticated user)
     * who are assigned the `class_coordinator` role.
     *
     * @param int|string $school_id School identifier
     * @param int|string $auth_id Authenticated user identifier
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getClasCooridnators($school_id, $auth_id)
    {
        return User::where([['id', '!=', $auth_id], ['school_id', $school_id], ['status', 'active']])->whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'class_coordinator');
            }
        );
    }
}
