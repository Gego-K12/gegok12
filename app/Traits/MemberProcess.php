<?php

/**
 * Trait for processing common
 */

namespace App\Traits;

use App\Http\Resources\ParentDetail as ParentDetailResource;
use App\Http\Resources\Teacher as TeacherResource;
use App\Http\Resources\Alumni as AlumniResource;
use App\Http\Resources\User as UserResource;
use App\Models\Users\TeacherUser;
use App\Models\Users\StudentUser;
use App\Models\Users\AlumniUser;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Log;

/**
 *
 * @class trait
 * Trait for MemberProcess Processes
 */
trait MemberProcess
{
    /**
     * Filter members (students) by role, status, and optional profile criteria.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param int $usergroup_id User group to filter by
     * @param string $status Default status filter
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function MemberFilter($request, $school_id, $usergroup_id, $status)
    {
        try {
            if ($usergroup_id == 6 && $request->status == 'active') {
                $users = StudentUser::BySchool($school_id)->ByRole($usergroup_id)->ByStatus($status);
            } else {
                $users = StudentUser::BySchool($school_id)->ByRole($usergroup_id);
            }


            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }

            $standard = $request->standard;
            if ($standard != '') {
                $users = $users->ByStandard($standard);
            }

            $status = $request->status;
            if ($status != '') {
                $users = $users->ByStatus($status);
            }

            $tag = $request->tag;
            if ($tag != '') {
                $users = $users->ByStudentTag($tag);
            }

            if ($status == '') {
                $users = $users->where('status', '!=', 'exit');
            }

            if (count((array)\Request::getQueryString()) > 0) {
                $firstname = $request->firstname;
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname;
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $gender = $request->gender;
                if ($gender != '') {
                    $users = $users->ByGender($gender);
                }

                $date_of_birth = $request->date_of_birth;
                if ($date_of_birth != '1970-01-01') {
                    if ($date_of_birth != '') {
                        $users = $users->ByDateOfBirth($date_of_birth);
                    }
                }

                $mobile_no = $request->mobile_no;
                if ($mobile_no != '') {
                    $users = $users->ByMobileNo($mobile_no);
                }

                $email = $request->email;
                if ($email != '') {
                    $users = $users->ByEmailId($email);
                }

                $blood_group = $request->blood_group;
                if ($blood_group != '') {
                    $users = $users->ByBloodGroup($blood_group);
                }

                $transport = $request->transport;
                if ($transport != '') {
                    $users = $users->ByTransport($transport);
                }

                $caste = $request->caste;
                if ($caste != '') {
                    $users = $users->ByCaste($caste);
                }
                $admission_number = $request->admission_number;
                if ($admission_number != '') {
                    $users = $users->ByAdmissionNumber($admission_number);
                }
                $status = $request->status;
                if ($status != '') {
                    $users = $users->ByStatus($status);
                }
            }
            $users = $users->get()->sortBy('userprofile.firstname');
            $users = UserResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Filter teacher users by role and profile attributes.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param int $usergroup_id Teacher role id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function TeacherFilter($request, $school_id, $usergroup_id)
    {
        try {
            $users = TeacherUser::where('school_id', $school_id)->ByRole($usergroup_id)->whereHas('userprofile', function ($q) {
                $q->where('status', 'active')->orWhere('status', 'inactive');
            });

            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }
            if (count((array)\Request::getQueryString()) > 0) {
                $firstname = $request->firstname;
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname;
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $gender = $request->gender;
                if ($gender != '') {
                    $users = $users->ByGender($gender);
                }

                $date_of_birth = $request->date_of_birth;
                if ($date_of_birth != '1970-01-01') {
                    if ($date_of_birth != '') {
                        $users = $users->ByDateOfBirth($date_of_birth);
                    }
                }

                $mobile_no = $request->mobile_no;
                if ($mobile_no != '') {
                    $users = $users->ByMobileNo($mobile_no);
                }

                $email = $request->email;
                if ($email != '') {
                    $users = $users->ByEmailId($email);
                }

                $blood_group = $request->blood_group;
                if ($blood_group != '') {
                    $users = $users->ByBloodGroup($blood_group);
                }

                $qualification = $request->qualification;
                if ($qualification != '') {
                    $users = $users->ByQualification($qualification);
                }

                $designation = $request->designation;
                if ($designation != '') {
                    $users = $users->ByDesignation($designation);
                }

                $marital_status = $request->marital_status;
                if ($marital_status != '') {
                    $users = $users->ByMaritalStatus($marital_status);
                }

                $job_type = $request->job_type;
                if ($job_type != '') {
                    $users = $users->ByJopType($job_type);
                }
            }
            $users = $users->get();
            $users = TeacherResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Filter non-teaching staff users by group and optional attributes.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param array $usergroup_id One or more staff role ids
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function StaffFilter($request, $school_id, $usergroup_id)
    {
        try {
            $users = User::where('school_id', $school_id)->whereIn('usergroup_id', $usergroup_id)->whereHas('userprofile', function ($q) {
                $q->where('status', 'active')->orWhere('status', 'inactive');
            });

            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }
            if (count((array)\Request::getQueryString()) > 0) {
                $firstname = $request->firstname;
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname;
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $gender = $request->gender;
                if ($gender != '') {
                    $users = $users->ByGender($gender);
                }

                $date_of_birth = $request->date_of_birth;
                if ($date_of_birth != '1970-01-01') {
                    if ($date_of_birth != '') {
                        $users = $users->ByDateOfBirth($date_of_birth);
                    }
                }

                $mobile_no = $request->mobile_no;
                if ($mobile_no != '') {
                    $users = $users->ByMobileNo($mobile_no);
                }

                $email = $request->email;
                if ($email != '') {
                    $users = $users->ByEmailId($email);
                }

                $blood_group = $request->blood_group;
                if ($blood_group != '') {
                    $users = $users->ByBloodGroup($blood_group);
                }

                $qualification = $request->qualification;
                if ($qualification != '') {
                    $users = $users->ByQualification($qualification);
                }

                $designation = $request->designation;
                if ($designation != '') {
                    $users = $users->ByDesignation($designation);
                }

                $marital_status = $request->marital_status;
                if ($marital_status != '') {
                    $users = $users->ByMaritalStatus($marital_status);
                }

                $job_type = $request->job_type;
                if ($job_type != '') {
                    $users = $users->ByJopType($job_type);
                }
            }
            $users = $users->get();
            $users = TeacherResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Filter parents with active children and optional profile attributes.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param int $usergroup_id Parent role id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function ParentFilter($request, $school_id, $usergroup_id)
    {
        try {
            $users = User::where('school_id', $school_id)->ByRole($usergroup_id)->whereHas('children', function ($q) use ($search) {

                $q->whereHas('userStudent', function ($q) {
                    $q->where([['status', '!=', 'exit']]);
                });
            })->whereHas('userprofile', function ($q) {
                $q->where('status', 'active')->orWhere('status', 'inactive');
            });

            if (count((array)\Request::getQueryString()) > 0) {
                $firstname = $request->firstname;
                if ($firstname != '') {
                    $users = $users->ByFirstNameParent($firstname);
                }

                $fullname = $request->fullname;
                if ($fullname != '') {
                    $users = $users->ByFullNameParent($fullname);
                }

                $student_name = $request->student_name;
                if ($student_name != '') {
                    $users = $users->ByStudentNameParent($student_name);
                }

                $lastname = $request->lastname;
                if ($lastname != '') {
                    $users = $users->ByLastNameParent($lastname);
                }

                $mobile_no = $request->mobile_no;
                if ($mobile_no != '') {
                    $users = $users->ByMobileNoParent($mobile_no);
                }

                $email = $request->email;
                if ($email != '') {
                    $users = $users->ByEmailParent($email);
                }

                $qualification = $request->qualification;
                if ($qualification != '') {
                    $users = $users->ByQualificationParent($qualification);
                }

                $occupation = $request->occupation;
                if ($occupation != '') {
                    $users = $users->ByOccupationParent($occupation);
                }

                $standardlink_id = $request->standardlink_id;
                if ($standardlink_id != '') {
                    $users = $users->ByStandardLinkParent($standardlink_id);
                }
            }
            $users = $users->paginate(10);
            $users = ParentDetailResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Filter alumni users by name alphabet and batch.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param int $usergroup_id Alumni role id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function AlumniFilter($request, $school_id, $usergroup_id)
    {
        try {
            $users = AlumniUser::where('school_id', $school_id)->ByRole($usergroup_id);

            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByName($alphabet);
            }

            $passing_session = $request->passing_session;
            if ($passing_session) {
                $users = $users->ByBatch($passing_session);
            }

            $users = $users->get();

            if (class_exists('Gegok12\Alumni\Http\Resources\Alumni')) //new
            {
                $users = \Gegok12\Alumni\Http\Resources\Alumni::collection($users);
            } else {
                $users = AlumniResource::collection($users);
            }

            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Filter alumni profiles excluding the current user.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param int $usergroup_id Alumni role id
     * @param int $user_id Current user id to exclude
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function AlumniProfileFilter($request, $school_id, $usergroup_id, $user_id)
    {
        try {
            $users = AlumniUser::where('school_id', $school_id)->ByRole($usergroup_id)->where('id', '!=', $user_id);

            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByName($alphabet);
            }

            $passing_session = $request->passing_session;
            if ($passing_session) {
                $users = $users->ByBatch($passing_session);
            }

            $users = $users->get();

            if (class_exists('Gegok12\Alumni\Http\Resources\Alumni')) //new
            {
                $users = \Gegok12\Alumni\Http\Resources\Alumni::collection($users);
            } else {
                $users = AlumniResource::collection($users);
            }

            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }
    //new
    /**
     * Filter library member records (students) using profile and library data.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param int $usergroup_id User group to filter by
     * @param string $status Default status filter
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function LibraryMemberFilter($request, $school_id, $usergroup_id, $status)
    {

        try {
            if ($usergroup_id == 6 && $request->status == 'active') {
                $users = StudentUser::BySchool($school_id)->ByRole($usergroup_id)->ByStatus($status);
            } else {
                $users = StudentUser::BySchool($school_id)->ByRole($usergroup_id);
            }


            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }

            $standard = $request->standard;
            if ($standard != '') {
                $users = $users->ByStandard($standard);
            }

            $status = $request->status;
            if ($status != '') {
                $users = $users->ByStatus($status);
            }

            if ($status == '') {
                $users = $users->where('status', '!=', 'exit');
            }

            if (count((array)\Request::getQueryString()) > 0) {
                $firstname = $request->firstname;

                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname;
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                //     $users = User::whereHas('libraryCard', function ($query) {
                //   $query->where('library_card_no', 'LC12345');})->get();


                //  $users = User::where('school_id',$school_id)->ByRole($usergroup_id)->whereHas('librarycard', function($q){
                //         $q->where('library_card_no','LC12345')->get();
                //     });
                $library_card_no = $request->library_card_no;
                if ($library_card_no != '') {
                    $users = $users->whereHas('librarycard', function ($query) use ($library_card_no) {
                        $query->where('library_card_no', $library_card_no);
                    });
                }
                $issue_date = $request->issue_date;

                if (!empty($issue_date)) {
                    $users = $users->whereHas('lending', function ($query) use ($issue_date) {
                        $query->whereDate('issue_date', $issue_date);  // use whereDate for date comparison
                    });
                }

                $return_date = $request->return_date;
                if (!empty($return_date)) {
                    $users = $users->whereHas('lending', function ($query) use ($return_date) {
                        $query->whereDate('return_date', $return_date);
                    });
                }

                $admission_number = $request->admission_number;
                if ($admission_number != '') {
                    $users = $users->ByAdmissionNumber($admission_number);
                }
            }
            $users = $users->get()->sortBy('userprofile.firstname');
            $users = UserResource::collection($users);

            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Filter library teachers by profile and library card/lending data.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param int $usergroup_id Teacher role id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function LibraryTeacherFilter($request, $school_id, $usergroup_id)
    {

        try {
            $users = TeacherUser::where('school_id', $school_id)->ByRole($usergroup_id)->whereHas('userprofile', function ($q) {
                $q->where('status', 'active')->orWhere('status', 'inactive');
            });

            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }
            if (count((array)\Request::getQueryString()) > 0) {
                $firstname = $request->firstname;
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname;
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }

                $library_card_no = $request->library_card_no;
                //    dd($library_card_no);
                if ($library_card_no != '') {
                    $users = $users->whereHas('librarycard', function ($query) use ($library_card_no) {
                        $query->where('library_card_no', $library_card_no);
                    });
                }

                $employee_id = $request->employee_id;
                if ($employee_id != '') {
                    $users = $users->ByEmployeeId($employee_id);
                }
                $issue_date = $request->issue_date;

                if (!empty($issue_date)) {
                    $users = $users->whereHas('lending', function ($query) use ($issue_date) {
                        $query->whereDate('issue_date', $issue_date);
                    });
                }

                $return_date = $request->return_date;

                if (!empty($return_date)) {
                    $users = $users->whereHas('lending', function ($query) use ($return_date) {
                        $query->whereDate('return_date', $return_date);
                    });
                }


                $email = $request->email;
                if ($email != '') {
                    $users = $users->ByEmailId($email);
                }
            }

            $users = $users->get();
            $users = TeacherResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Filter library staff by profile and library card/lending data.
     *
     * @param \Illuminate\Http\Request $request Incoming request with filter params
     * @param int $school_id School identifier
     * @param array $usergroup_id Staff role ids
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function LibraryStaffFilter($request, $school_id, $usergroup_id)
    {
        try {
            $users = User::where('school_id', $school_id)->whereIn('usergroup_id', $usergroup_id)->whereHas('userprofile', function ($q) {
                $q->where('status', 'active')->orWhere('status', 'inactive');
            });

            $alphabet = $request->alphabet ? $request->alphabet : '';
            if ($alphabet) {
                $users = $users->ByFirstName($alphabet);
            }
            if (count((array)\Request::getQueryString()) > 0) {
                $firstname = $request->firstname;
                if ($firstname != '') {
                    $users = $users->ByFirstName($firstname);
                }

                $lastname = $request->lastname;
                if ($lastname != '') {
                    $users = $users->ByLastName($lastname);
                }
                $library_card_no = $request->library_card_no;

                if ($library_card_no != '') {
                    $users = $users->whereHas('librarycard', function ($query) use ($library_card_no) {
                        $query->where('library_card_no', $library_card_no);
                    });
                }

                $employee_id = $request->employee_id;
                if ($employee_id != '') {
                    $users = $users->ByEmployeeId($employee_id);
                }
                $issue_date = $request->issue_date;

                if (!empty($issue_date)) {
                    $users = $users->whereHas('lending', function ($query) use ($issue_date) {
                        $query->whereDate('issue_date', $issue_date);
                    });
                }

                $return_date = $request->return_date;
                if (!empty($return_date)) {
                    $users = $users->whereHas('lending', function ($query) use ($return_date) {
                        $query->whereDate('return_date', $return_date);
                    });
                }
            }

            $users = $users->get();
            $users = TeacherResource::collection($users);
            return $users;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
