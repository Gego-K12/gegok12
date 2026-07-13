<?php

/**
 * Trait for processing RegisterUser
 */

namespace App\Traits;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\ParentProfile;
use App\Models\Scholastic;
use App\Models\StudentAcademic;
use App\Models\StudentParentLink;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use Exception;
use Illuminate\Support\Str;
use Log;

/**
 * @class trait
 * Trait for RegisterUser Processes
 */
trait RegisterUser
{
    /**
     * Create a new student user with profile and academic details.
     *
     * @param  object  $data  Request payload containing user, profile, and academic info
     * @param  int  $school_id  School identifier
     * @param  int  $academic_year_id  Academic year identifier
     * @param  string  $path  Avatar path if available
     * @param  int  $usergroup_id  User group id for the student
     * @return User Newly created user
     */
    public function CreateUser($data, $school_id, $academic_year_id, $path, $usergroup_id)
    {
        \DB::beginTransaction();
        try {
            $user = new User;
            $user->school_id = $school_id;
            $user->usergroup_id = $usergroup_id;

            if (! is_null($data->name)) {
                $user->name = $data->name;
            }

            $user->password = bcrypt('password'); // demo
            $user->email = $data->email;
            $user->mobile_no = $data->mobile_no;
            $user->email_verification_code = Str::random(40);
            $user->registration_number = $data->registration_number;

            $user->save();

            $userprofile = new Userprofile;

            $userprofile->school_id = $school_id;
            $userprofile->user_id = $user->id;
            $userprofile->usergroup_id = $usergroup_id;
            $userprofile->firstname = $data->firstname;
            if (! is_null($data->lastname)) {
                $userprofile->lastname = $data->lastname;
            }
            $userprofile->gender = $data->gender;

            $userprofile->date_of_birth = date('Y-m-d', strtotime($data->date_of_birth));

            $userprofile->blood_group = $data->blood_group;

            $userprofile->address = $data->address;

            $userprofile->city_id = $data->city_id;

            $userprofile->state_id = $data->state_id;

            $userprofile->country_id = $data->country_id;

            $userprofile->pincode = $data->pincode;

            if (! is_null($data->birth_place)) {
                $userprofile->birth_place = $data->birth_place;
            }
            if (! is_null($data->native_place)) {
                $userprofile->native_place = $data->native_place;
            }
            if (! is_null($data->mother_tongue)) {
                $userprofile->mother_tongue = $data->mother_tongue;
            }
            if (! is_null($data->caste)) {
                $userprofile->caste = $data->caste;
            }

            $userprofile->sub_caste = $data->sub_caste;

            $userprofile->aadhar_number = $data->aadhar_number;

            $userprofile->joining_date = date('Y-m-d', strtotime($data->joining_date));

            /* $userprofile->registration_number   = $data->registration_number; */

            if (! is_null($data->EMIS_number)) {
                $userprofile->EMIS_number = $data->EMIS_number;
            }
            if (! is_null($data->notes)) {
                $userprofile->notes = $data->notes;
            }

            if ($path != '') {
                $userprofile->avatar = $path;
            } else {
                if ($userprofile->gender == 'male') {
                    $userprofile->avatar = 'uploads/male.png';
                } elseif ($userprofile->gender == 'female') {
                    $userprofile->avatar = 'uploads/female.png';
                } else {
                    $userprofile->avatar = 'uploads/images.jpg';
                }
            }

            $userprofile->save();

            $academic = new StudentAcademic;

            $academic->school_id = $school_id;
            $academic->academic_year_id = $academic_year_id;
            $academic->user_id = $user->id;
            $academic->standardLink_id = $data->standard;
            $academic->roll_number = $data->roll_number;
            $academic->id_card_number = $data->id_card_number;
            $academic->board_registration_number = $data->board_registration_number;
            $academic->mode_of_transport = $data->mode_of_transport;
            if (($data->mode_of_transport == 'auto') || ($data->mode_of_transport == 'rickshaw') || ($data->mode_of_transport == 'taxi')) {
                $array['driver_name'] = $data->driver_name;
                $array['driver_contact_number'] = $data->driver_contact_number;

                $academic->transport_details = $array;
            }

            $academic->save();

            \DB::commit();

            return $user;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Update an existing student user profile and academic record.
     *
     * @param  object  $data  Request payload with updated fields
     * @param  int  $school_id  School identifier
     * @param  int  $academic_year_id  Academic year identifier
     * @param  int  $user_id  User identifier to update
     * @param  string  $path  Avatar path if replaced
     * @return Userprofile Updated profile
     */
    public function UpdateUser($data, $school_id, $academic_year_id, $user_id, $path)
    {
        \DB::beginTransaction();
        try {
            $user = User::where('id', $user_id)->first();
            if (! is_null($data->registration_number)) {
                $user->registration_number = $data->registration_number;
            }
            $user->save();
            $userprofile = Userprofile::where('user_id', $user_id)->first();

            $userprofile->firstname = $data->firstname;
            if (! is_null($data->lastname)) {
                $userprofile->lastname = $data->lastname;
            }
            $userprofile->gender = $data->gender;

            $userprofile->date_of_birth = date('Y-m-d', strtotime($data->date_of_birth));

            $userprofile->blood_group = $data->blood_group;

            $userprofile->address = $data->address;

            $userprofile->city_id = $data->city_id;

            $userprofile->state_id = $data->state_id;

            $userprofile->country_id = $data->country_id;

            $userprofile->pincode = $data->pincode;

            if (! is_null($data->birth_place)) {
                $userprofile->birth_place = $data->birth_place;
            }
            if (! is_null($data->native_place)) {
                $userprofile->native_place = $data->native_place;
            }
            if (! is_null($data->mother_tongue)) {
                $userprofile->mother_tongue = $data->mother_tongue;
            }
            if (! is_null($data->caste)) {
                $userprofile->caste = $data->caste;
            }

            $userprofile->sub_caste = $data->sub_caste;

            $userprofile->aadhar_number = $data->aadhar_number;

            $userprofile->joining_date = date('Y-m-d', strtotime($data->joining_date));
            if (! is_null($data->EMIS_number)) {
                $userprofile->EMIS_number = $data->EMIS_number;
            }
            if (! is_null($data->notes)) {
                $userprofile->notes = $data->notes;
            }

            if ($path != '') {
                $userprofile->avatar = $path;
            } else {
                if ($userprofile->gender == 'male') {
                    $userprofile->avatar = 'uploads/male.png';
                } elseif ($userprofile->gender == 'female') {
                    $userprofile->avatar = 'uploads/female.png';
                } else {
                    $userprofile->avatar = 'uploads/images.jpg';
                }
            }

            $userprofile->save();

            $previousAcademic = StudentAcademic::where('user_id', $user_id)->orderByDesc('id')->first();
            $previousSiblings = $previousAcademic->siblings ?? null;
            $previousSiblingsCount = $previousAcademic->siblings_count ?? null;
            $previousSiblingDetails = $previousAcademic->sibling_details ?? null;

            $studentAcademic = StudentAcademic::where('user_id', $user_id)->get();

            foreach ($studentAcademic as $academics) {
                $academics->delete();
            }

            $academic = new StudentAcademic;

            $academic->school_id = $school_id;
            $academic->academic_year_id = $academic_year_id;
            $academic->user_id = $user_id;
            $academic->standardLink_id = $data->standard;
            $academic->roll_number = $data->roll_number;
            $academic->id_card_number = $data->id_card_number;
            $academic->board_registration_number = $data->board_registration_number;
            $academic->mode_of_transport = $data->mode_of_transport;
            if (($data->mode_of_transport == 'auto') || ($data->mode_of_transport == 'rickshaw') || ($data->mode_of_transport == 'taxi')) {
                $array['driver_name'] = $data->driver_name;
                $array['driver_contact_number'] = $data->driver_contact_number;

                $academic->transport_details = $array;
            }

            // Siblings are captured via the parent add/edit forms, not this form; carry the existing values forward.
            $academic->siblings = $previousSiblings;
            $academic->siblings_count = $previousSiblingsCount;
            $academic->sibling_details = $previousSiblingDetails;

            $academic->save();

            \DB::commit();

            return $userprofile;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Create a parent user and link to a student.
     *
     * @param  int|null  $student_id  Student identifier to attach (optional)
     * @param  object  $data  Request payload containing parent details
     * @param  int  $school_id  School identifier
     * @param  int  $usergroup_id  Parent role id
     * @return User Parent user model
     */
    public function CreateParent($student_id, $data, $school_id, $usergroup_id)
    {
        \DB::beginTransaction();
        try {
            if ($data->parent == 'add') {
                $user = new User;

                $user->school_id = $school_id;
                $user->usergroup_id = $usergroup_id;
                if (! is_null($data->name)) {
                    $user->name = $data->name;
                }
                $user->password = bcrypt('password'); // demo
                $user->email = $data->email;
                $user->mobile_no = $data->mobile_no;
                $user->email_verification_code = Str::random(40);

                $user->save();

                $userprofile = new Userprofile;

                $userprofile->school_id = $school_id;
                $userprofile->user_id = $user->id;
                $userprofile->usergroup_id = $usergroup_id;
                $userprofile->firstname = $data->firstname;
                if (! is_null($data->lastname)) {
                    $userprofile->lastname = $data->lastname;
                }
                if (! is_null($data->alternate_no)) {
                    $userprofile->alternate_no = $data->alternate_no;
                }

                $userprofile->save();

                if ($data->qualification_id != null) {
                    foreach ($data->qualification_id as $qualification) {

                        $parent = new ParentProfile;

                        $parent->school_id = $school_id;
                        $parent->user_id = $user->id;
                        $parent->qualification_id = $qualification;
                        $parent->profession = $data->profession;
                        $parent->sub_occupation = $data->sub_occupation;
                        $parent->designation = $data->designation;
                        $parent->organization_name = $data->organization_name;
                        $parent->official_address = $data->official_address;
                        $parent->relation = $data->relation;
                        $parent->annual_income = $data->annual_income;

                        $parent->save();
                    }
                } else {
                    $parent = new ParentProfile;

                    $parent->school_id = $school_id;
                    $parent->user_id = $user->id;
                    $parent->profession = $data->profession;
                    $parent->sub_occupation = $data->sub_occupation;
                    $parent->designation = $data->designation;
                    $parent->organization_name = $data->organization_name;
                    $parent->official_address = $data->official_address;
                    $parent->relation = $data->relation;
                    $parent->annual_income = $data->annual_income;

                    $parent->save();
                }
            } else {
                $user = User::where('school_id', $school_id)->where('id', $data->select_id)->first();
            }

            if ($student_id != null) {
                $student = User::where('id', $student_id)->first();
            } else {
                $student = User::where('name', $data->ref_name)->first();
            }
            $studentparentlink = StudentParentLink::where([['school_id', $school_id], ['student_id', $student->id], ['parent_id', $user->id]])->first();

            if ($studentparentlink == null) {
                $link = new StudentParentLink;

                $link->school_id = $school_id;
                $link->parent_id = $user->id;
                $link->student_id = $student->id;
                $link->status = 1;

                $link->save();
            }

            $academic = StudentAcademic::where('user_id', $student->id)->orderByDesc('id')->first();

            if ($academic != null) {
                $academic->siblings = $data->siblings;
                $academic->siblings_count = $data->siblings_count;

                if ($data->siblings == 'yes') {
                    $array = [];

                    for ($i = 0; $i < $data->siblings_count; $i++) {
                        $array[$i]['sibling_relation'] = $data->sibling_relation[$i];
                        $array[$i]['sibling_name'] = $data->sibling_name[$i];
                        $array[$i]['sibling_date_of_birth'] = date('Y-m-d', strtotime($data->sibling_date_of_birth[$i]));
                        $array[$i]['sibling_standard'] = $data->sibling_standard[$i];
                    }

                    $academic->sibling_details = $array;
                }

                $academic->save();
            }

            \DB::commit();

            return $user;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Update parent profile details and qualifications.
     *
     * @param  int  $student_id  Associated student id
     * @param  object  $data  Request payload containing parent updates
     * @param  int  $school_id  School identifier
     * @param  int  $user_id  Parent user identifier
     * @return Userprofile Updated parent profile
     */
    public function UpdateParent($student_id, $data, $school_id, $user_id)
    {
        \DB::beginTransaction();
        try {
            $userprofile = Userprofile::where('user_id', $user_id)->first();

            $userprofile->firstname = $data->firstname;
            if (! is_null($data->lastname)) {
                $userprofile->lastname = $data->lastname;
            }
            if (! is_null($data->alternate_no)) {
                $userprofile->alternate_no = $data->alternate_no;
            }

            $userprofile->save();

            $parentprofiles = ParentProfile::where('user_id', $user_id)->get();
            foreach ($parentprofiles as $profile) {
                $profile->delete();
            }

            if ($data->qualification_id != null) {
                foreach ($data->qualification_id as $qualification) {

                    $parent = new ParentProfile;

                    $parent->school_id = $school_id;
                    $parent->user_id = $user_id;
                    $parent->qualification_id = $qualification;
                    $parent->profession = $data->profession;
                    $parent->sub_occupation = $data->sub_occupation;
                    $parent->designation = $data->designation;
                    $parent->organization_name = $data->organization_name;
                    $parent->official_address = $data->official_address;
                    $parent->relation = $data->relation;
                    $parent->annual_income = $data->annual_income;

                    $parent->save();
                }
            } else {
                $parent = new ParentProfile;

                $parent->school_id = $school_id;
                $parent->user_id = $user_id;
                $parent->profession = $data->profession;
                $parent->sub_occupation = $data->sub_occupation;
                $parent->designation = $data->designation;
                $parent->organization_name = $data->organization_name;
                $parent->official_address = $data->official_address;
                $parent->relation = $data->relation;
                $parent->annual_income = $data->annual_income;

                $parent->save();
            }

            if ($student_id != null) {
                $student = User::where('id', $student_id)->first();
            } elseif (! empty($data->ref_name)) {
                $student = User::where('name', $data->ref_name)->first();
            } else {
                $link = StudentParentLink::where('parent_id', $user_id)->orderByDesc('id')->first();
                $student = $link != null ? User::where('id', $link->student_id)->first() : null;
            }

            if ($student != null) {
                $academic = StudentAcademic::where('user_id', $student->id)->orderByDesc('id')->first();

                if ($academic != null) {
                    $academic->siblings = $data->siblings;
                    $academic->siblings_count = $data->siblings_count;

                    if ($data->siblings == 'yes') {
                        $array = [];

                        for ($i = 0; $i < $data->siblings_count; $i++) {
                            $array[$i]['sibling_relation'] = $data->sibling_relation[$i];
                            $array[$i]['sibling_name'] = $data->sibling_name[$i];
                            $array[$i]['sibling_date_of_birth'] = date('Y-m-d', strtotime($data->sibling_date_of_birth[$i]));
                            $array[$i]['sibling_standard'] = $data->sibling_standard[$i];
                        }

                        $academic->sibling_details = $array;
                    }

                    $academic->save();
                }
            }

            \DB::commit();

            return $userprofile;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Create a teacher user with profile and teacher-specific attributes.
     *
     * @param  object  $data  Request payload with teacher info
     * @param  int  $school_id  School identifier
     * @param  AcademicYear  $academic_year  Academic year model
     * @param  string  $path  Avatar path if provided
     * @param  int  $usergroup_id  Teacher role id
     * @return User Newly created teacher
     */
    public function CreateTeacher($data, $school_id, $academic_year, $path, $usergroup_id)
    {
        \DB::beginTransaction();
        try {
            $user = new User;
            $user->school_id = $school_id;
            $user->usergroup_id = $usergroup_id;

            if (! is_null($data->name)) {
                $user->name = $data->name;
            }

            $user->password = bcrypt('password'); // demo
            $user->email = $data->email;
            $user->mobile_no = $data->mobile_no;
            $user->email_verification_code = Str::random(40);

            $user->save();

            $userprofile = new Userprofile;

            $userprofile->school_id = $school_id;
            $userprofile->user_id = $user->id;
            $userprofile->usergroup_id = $usergroup_id;
            $userprofile->firstname = $data->firstname;
            if (! is_null($data->lastname)) {
                $userprofile->lastname = $data->lastname;
            }

            $userprofile->gender = $data->gender;

            $userprofile->date_of_birth = date('Y-m-d', strtotime($data->date_of_birth));

            $userprofile->blood_group = $data->blood_group;

            $userprofile->address = $data->address;

            $userprofile->city_id = $data->city_id;

            $userprofile->state_id = $data->state_id;

            $userprofile->country_id = $data->country_id;

            $userprofile->pincode = $data->pincode;

            $userprofile->aadhar_number = $data->aadhar_number;

            $userprofile->joining_date = date('Y-m-d', strtotime($data->joining_date));

            if (! is_null($data->notes)) {
                $userprofile->notes = $data->notes;
            }

            if ($path != '') {
                $userprofile->avatar = $path;
            } else {
                if ($userprofile->gender == 'male') {
                    $userprofile->avatar = 'uploads/male.png';
                } elseif ($userprofile->gender == 'female') {
                    $userprofile->avatar = 'uploads/female.png';
                } else {
                    $userprofile->avatar = 'uploads/user/avatar/default-user.jpg';
                }
            }

            $userprofile->marital_status = $data->marital_status;

            $userprofile->save();

            if ($data->qualification_id == null) {
                $teacherprofile = new TeacherProfile;

                $teacherprofile->school_id = $school_id;
                $teacherprofile->academic_year_id = $academic_year->id;
                $teacherprofile->user_id = $user->id;
                $teacherprofile->qualification_id = $data->qualification_id;
                /*if($teacherprofile->qualification_id == 1)
                {
                    $teacherprofile->sub_qualification    = $data->sub_qualification;
                }*/
                $teacherprofile->ug_degree = $data->ug_degree;
                $teacherprofile->pg_degree = $data->pg_degree;
                $teacherprofile->specialization = $data->specialization;
                $teacherprofile->designation = $data->designation;
                $teacherprofile->sub_designation = $data->sub_designation;
                $teacherprofile->employee_id = $data->employee_id;
                $teacherprofile->job_type = $data->job_type;
                $teacherprofile->interested_in = $data->interested_in;
                $teacherprofile->reporting_to = $data->reporting_to;
                $teacherprofile->status = 1;

                $teacherprofile->save();
            } else {
                foreach ($data->qualification_id as $qualification) {
                    $teacherprofile = new TeacherProfile;

                    $teacherprofile->school_id = $school_id;
                    $teacherprofile->academic_year_id = $academic_year->id;
                    $teacherprofile->user_id = $user->id;
                    $teacherprofile->qualification_id = $qualification;
                    if ($teacherprofile->qualification_id == 1) {
                        $teacherprofile->sub_qualification = $data->sub_qualification;
                    }
                    $teacherprofile->ug_degree = $data->ug_degree;
                    $teacherprofile->pg_degree = $data->pg_degree;
                    $teacherprofile->specialization = $data->specialization;
                    $teacherprofile->designation = $data->designation;
                    $teacherprofile->sub_designation = $data->sub_designation;
                    $teacherprofile->employee_id = $data->employee_id;
                    $teacherprofile->job_type = $data->job_type;
                    $teacherprofile->interested_in = $data->interested_in;
                    $teacherprofile->reporting_to = $data->reporting_to;
                    $teacherprofile->status = 1;

                    $teacherprofile->save();
                }
            }

            if ($data->designation == 'principal') {
                $user->addRole('principal');
            }

            if ($data->designation == 'transport_coordinator') {
                $user->addRole('transport_coordinator');
            }

            if ($data->designation == 'driver') {
                $user->addRole('transport_driver');
            }

            if (($data->designation == 'principal') || ($data->designation == 'vice_principal') || ($data->designation == 'head_of_the_department')) {
                $user->addRole('leave_checker');
                $user->addRole('class_coordinator');

            } else {
                $user->addRole('leave_applier');
            }

            \DB::commit();

            return $user;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Create or update a mark entry for a student exam.
     *
     * @param  object  $data  Payload with mark details and associations
     * @param  int  $school_id  School identifier
     * @param  AcademicYear  $academic_year  Academic year model
     * @param  int  $sc_grade  Scholastic grading scheme id
     * @param  int|null  $non_sc_grade  Non-scholastic grading scheme id
     * @return Mark|\Gegok12\Exam\Models\Mark Persisted mark model
     */
    public function CreateMark($data, $school_id, $academic_year, $sc_grade, $non_sc_grade)
    {
        \DB::beginTransaction();
        try {
            $sc_grade = Scholastic::where('id', $sc_grade)->first();

            if (class_exists('Gegok12\Exam\Models\Mark')) {
                $mark = new \Gegok12\Exam\Models\Mark;
            } else {
                $mark = new Mark;
            }

            $mark->school_id = $school_id;
            $mark->academic_year_id = $academic_year->id;
            $mark->standard_id = $data->standard_id;
            $mark->subject_id = $data->subject_id;
            $mark->exam_id = $data->exam_id;
            // $mark->grade_name         =  $data->grade_name;
            $mark->user_id = $data->user_id;
            $mark->teacher_comment = $data->teacher_comment;
            $mark->obtained_marks = $data->obtained_marks;
            // $mark->total_marks        =  $data->total_marks;

            if ($non_sc_grade != null) {
                $mark->grades_awarded = $data->grades_awarded;
            }
            if ($sc_grade->grading_method == 'cbse') {

                $mark->grade_name = $mark->getGrade($mark->obtained_marks);
            } else {
                $mark->grade_name = $mark->getPassFail($mark->obtained_marks);
            }

            if ($mark->obtained_marks == '') {
                $mark->student_status = 'absent';
            } else {
                $mark->student_status = 'present';
            }

            $mark->save();

            \DB::commit();

            return $mark;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Create or fetch an alumni user and seed profile data.
     *
     * @param  object  $data  Request payload with alumni details
     * @param  int  $usergroup_id  Alumni role id
     * @param  int  $school_id  School identifier
     * @param  string  $passing_session  Passing year/batch
     * @return User Alumni user model
     */
    public function AddAlumni($data, $usergroup_id, $school_id, $passing_session)
    {
        \DB::beginTransaction();
        try {
            $user = User::where('email', $data->email)
                    // ->orWhere('mobile_no', $data->mobile_no)
                    // ->orWhere('registration_number', $data->registration_number)
                ->first();

            $path = '';

            if (! $user) {
                $user = new User;

                $user->school_id = $school_id;
                $user->usergroup_id = $usergroup_id;
                $user->name = $data->name;
                $user->password = bcrypt('password'); // demo
                $user->email = $data->email;
                $user->mobile_no = $data->mobile_no;
                if ($data->email_verification_code != null) {
                    $user->email_verification_code = $data->email_verification_code;
                } else {
                    $user->email_verification_code = Str::random(40);
                }

                if ($data->email_verification_code != null) {
                    $user->registration_number = $data->registration_number;
                }

                $user->save();

                // $data->passing_session = $passing_session;

                $userprofile = new Userprofile;

                $userprofile->school_id = $school_id;
                $userprofile->user_id = $user->id;
                $userprofile->usergroup_id = $usergroup_id;
                $userprofile->firstname = $data->name;
                $userprofile->avatar = 'uploads/male.png';
                $userprofile->save();
            } else {
                $data->passing_session = $passing_session;
            }

            $alumniprofile = $this->CreateAlumni($data, $path, $usergroup_id, $school_id, $user);

            \DB::commit();

            return $user;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Create an alumni profile record for an existing user.
     *
     * @param  object  $data  Alumni profile payload
     * @param  string  $path  Photo path
     * @param  int  $usergroup_id  Alumni role id
     * @param  int  $school_id  School identifier
     * @param  User  $user  User to link
     * @return mixed Persisted alumni profile
     */
    public function CreateAlumni($data, $path, $usergroup_id, $school_id, $user)
    {
        \DB::beginTransaction();
        try {
            if (class_exists('Gegok12\Alumni\Models\Alumniprofile')) {
                $alumni = new \Gegok12\Alumni\Models\Alumniprofile;

                $alumni->school_id = $school_id;
                $alumni->user_id = $user->id;
                $alumni->name = $user->name;
                $alumni->email = $user->email;
                $alumni->mobile_no = $user->mobile_no;
                $alumni->passing_session = $data->passing_session;
                $alumni->photo = $path;

                $alumni->institution_name = $data->institution_name;
                $alumni->degree = $data->degree;
                $alumni->specialization = $data->specialization;
                $alumni->college_start_year = $data->college_start_year;
                if ($data->current_studying != 1) {
                    $alumni->college_end_year = $data->college_end_year;
                    $alumni->grade = $data->grade;
                } else {
                    $alumni->college_end_year = null;
                    $alumni->grade = null;
                }

                $alumni->company_name = $data->company_name;
                $alumni->designation = $data->designation;
                $alumni->location = $data->location;
                $alumni->job_start_year = $data->job_start_year;
                $alumni->job_start_month = $data->job_start_month;
                if ($data->present != 1) {
                    $alumni->job_end_year = $data->job_end_year;
                    $alumni->job_end_month = $data->job_end_month;
                } else {
                    $alumni->job_end_year = null;
                    $alumni->job_end_month = null;
                }

                $alumni->twitter = $data->twitter;
                $alumni->linkedin = $data->linkedin;
                $alumni->telegram = $data->telegram;
                $alumni->facebook = $data->facebook;
                $alumni->about_me = $data->about_me;

                $alumni->save();
            }

            \DB::commit();

            return $alumni;
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
