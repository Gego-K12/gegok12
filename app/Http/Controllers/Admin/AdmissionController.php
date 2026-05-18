<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admission as AdmissionResource;
use App\Http\Requests\Admission\AdmissionFormRequest;
use App\Http\Resources\FeeGroup as FeeGroupResource;
use App\Http\Resources\Section as SectionResource;
use App\Events\AdmissionApprovalEvent;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\AdmissionUser;
use App\Traits\SmsProcess;
use App\Models\StandardLink;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Admission;
use App\Models\FeeGroup;
use App\Traits\Common;
use App\Models\Fee;
use Exception;
use Log;
/**
 * Class AdmissionController
 *
 * Controller for managing student admissions: listing, viewing,
 * approving, updating and deleting admission records.
 *
 * @package App\Http\Controllers\Admin
 */
class AdmissionController extends Controller
{  
    use AdmissionUser;
    use LogActivity;
    use SmsProcess;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request ) 
    {
        return view('/admin/admission/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admissionlist(Request $request)
    {
        $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

        $query = Admission::where([
            ['school_id', Auth::user()->school_id],
            ['academic_year_id', $academic_year->id]
        ]);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('application_status', $request->status);
        }

        $admissions = $query->orderBy('created_at', 'desc')->paginate(10);
        $admissions = AdmissionResource::collection($admissions);

        return $admissions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admission = Admission::where('school_id', Auth::user()->school_id)
            ->where('id', $id)
            ->first();

        if (!$admission) {
            return response()->json(['error' => 'Admission not found'], 404);
        }

        $sections = StandardLink::where([
            ['school_id', Auth::user()->school_id],
            ['standard_id', $admission->standard_id]
        ])->get();

        $standardlist = \DB::table('standards')
            
            ->orderBy('order')
            ->get();

        $qualificationlist = \DB::table('qualifications')
            
            
            ->orderBy('display_name')
            ->get();

        // Get standard name
        $standard = \DB::table('standards')->where('id', $admission->standard_id)->first();

        // Get qualification names for father and mother
        $father_qualification = $admission->father_qualification_id 
            ? \DB::table('qualifications')->where('id', $admission->father_qualification_id)->first() 
            : null;
        $mother_qualification = $admission->mother_qualification_id 
            ? \DB::table('qualifications')->where('id', $admission->mother_qualification_id)->first() 
            : null;

        // Get change logs
        $changeLogs = \DB::table('admission_change_logs')
            ->where('admission_id', $id)
            ->join('users', 'admission_change_logs.changed_by', '=', 'users.id')
            ->select('admission_change_logs.*', 'users.name as changed_by_name')
            ->orderBy('changed_at', 'desc')
            ->get();

        $array = [];
        // All admission fields
        $array['id']                        = $admission->id;
        $array['application_no']            = $admission->application_no;
        $array['application_status']        = $admission->application_status;
        $array['standard_id']               = $admission->standard_id;
        $array['standard_name']             = $standard ? $standard->name : '';
        $array['section_id']                = $admission->section_id;

        // Student details
        $array['name']                      = $admission->name;
        $array['date_of_birth']             = $admission->date_of_birth;
        $array['gender']                    = $admission->gender;
        $array['height']                    = $admission->height;
        $array['weight']                    = $admission->weight;
        $array['birth_place']               = $admission->birth_place;
        $array['nationality']               = $admission->nationality;
        $array['avatar']                    = $admission->avatar;
        $array['religion']                  = $admission->religion;
        $array['community']                 = $admission->community;
        $array['mother_tongue']             = $admission->mother_tongue;
        $array['identification_marks']      = $admission->identification_marks;
        $array['aadhar_number']             = $admission->aadhar_number;
        $array['blood_group']               = $admission->blood_group;
        $array['school_last_studied']       = $admission->school_last_studied;
        $array['reason_for_leaving']        = $admission->reason_for_leaving;
        $array['permanent_address']         = $admission->permanent_address;
        $array['address_for_communication'] = $admission->address_for_communication;
        $array['siblings']                  = $admission->siblings;

        // Academic details
        $array['half_yearly_mark_details']  = $admission->half_yearly_mark_details;
        $array['board_of_education']        = $admission->board_of_education;
        $array['choice_of_language']        = $admission->choice_of_language;
        $array['group_selection']           = $admission->group_selection;
        $array['board_registration_number'] = $admission->board_registration_number;

        // Father details
        $array['father_name']               = $admission->father_name;
        $array['father_qualification_id']   = $admission->father_qualification_id;
        $array['father_qualification_name'] = $father_qualification ? $father_qualification->display_name : '';
        $array['father_designation']        = $admission->father_designation;
        $array['father_occupation']         = $admission->father_occupation;
        $array['father_organisation']       = $admission->father_organisation;
        $array['father_income']             = $admission->father_income;
        $array['father_mobile_no']          = $admission->father_mobile_no;
        $array['father_email']              = $admission->father_email;
        $array['father_aadhar_number']      = $admission->father_aadhar_number;
        $array['father_avatar']             = $admission->father_avatar;

        // Mother details
        $array['mother_name']               = $admission->mother_name;
        $array['mother_qualification_id']   = $admission->mother_qualification_id;
        $array['mother_qualification_name'] = $mother_qualification ? $mother_qualification->display_name : '';
        $array['mother_designation']        = $admission->mother_designation;
        $array['mother_occupation']         = $admission->mother_occupation;
        $array['mother_organisation']       = $admission->mother_organisation;
        $array['mother_income']             = $admission->mother_income;
        $array['mother_mobile_no']          = $admission->mother_mobile_no;
        $array['mother_email']              = $admission->mother_email;
        $array['mother_aadhar_number']      = $admission->mother_aadhar_number;
        $array['mother_avatar']             = $admission->mother_avatar;

        // Emergency contacts
        $array['emergency_contact_1']       = $admission->emergency_contact_1;
        $array['relation_with_student_1']   = $admission->relation_with_student_1;
        $array['emergency_contact_2']       = $admission->emergency_contact_2;
        $array['relation_with_student_2']   = $admission->relation_with_student_2;

        // Personal details
        $array['medical_history']           = $admission->medical_history;
        $array['medical_details']           = $admission->medical_details;
        $array['extra_curricular_activities'] = $admission->extra_curricular_activities;
        $array['activities']                = $admission->activities;
        $array['mode_of_transport']         = $admission->mode_of_transport;
        $array['transport_details']         = $admission->transport_details;

        // Payment/fees
        $array['payment_status']            = $admission->payment_status;
        $array['fee_group_id']              = $admission->fee_group_id;
        $array['remarks']                   = $admission->remarks;
        $array['principal_notes']            = $admission->principal_notes;

        // Timestamps
        $array['created_at']                = $admission->created_at ? $admission->created_at->format('d-M-Y h:i A') : '';

        // Lookups
        $array['sectionlist']               = SectionResource::collection($sections);
        $array['standardlist']              = $standardlist;
        $array['qualificationlist']         = $qualificationlist;
        $array['change_logs']               = $changeLogs;

        if (class_exists('Gegok12\Fee\Models\FeeGroup')) {
            $fee = \Gegok12\Fee\Models\FeeGroup::where('school_id', Auth::user()->school_id)->get();
            $array['feelist'] = class_exists('Gegok12\Fee\Http\Resources\FeeGroup')
                ? \Gegok12\Fee\Http\Resources\FeeGroup::collection($fee)
                : [];
        }

        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admission = Admission::where('id',$id)->first();

        return view('/admin/admission/edit' , ['admission' => $admission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            $admission = Admission::where('id', $id)->first();

            if (!$admission) {
                return response()->json(['error' => 'Admission not found'], 404);
            }

            // Fields that can be edited
            $editableFields = [
                'application_status', 'section_id', 'fee_group_id', 'payment_status', 'remarks', 'principal_notes',
                'name', 'date_of_birth', 'gender', 'height', 'weight',
                'birth_place', 'nationality', 'religion', 'community', 'mother_tongue',
                'identification_marks', 'aadhar_number', 'blood_group',
                'school_last_studied', 'reason_for_leaving',
                'permanent_address', 'address_for_communication', 'siblings',
                'half_yearly_mark_details', 'board_of_education', 'choice_of_language',
                'group_selection', 'board_registration_number',
                'father_name', 'father_qualification_id', 'father_designation',
                'father_occupation', 'father_organisation', 'father_income',
                'father_mobile_no', 'father_email', 'father_aadhar_number',
                'mother_name', 'mother_qualification_id', 'mother_designation',
                'mother_occupation', 'mother_organisation', 'mother_income',
                'mother_mobile_no', 'mother_email', 'mother_aadhar_number',
                'emergency_contact_1', 'relation_with_student_1',
                'emergency_contact_2', 'relation_with_student_2',
                'medical_history', 'medical_details',
                'extra_curricular_activities', 'activities',
                'mode_of_transport', 'transport_details',
                'standard_id',
            ];

            // Log changes
            foreach ($editableFields as $field) {
                if ($request->has($field)) {
                    $oldValue = $admission->$field;
                    $newValue = $request->$field;

                    // Only log if value actually changed
                    if ((string)$oldValue !== (string)$newValue) {
                        \DB::table('admission_change_logs')->insert([
                            'admission_id' => $id,
                            'field_name'   => $field,
                            'old_value'    => $oldValue,
                            'new_value'    => $newValue,
                            'changed_by'   => Auth::user()->id,
                            'changed_at'   => now(),
                        ]);

                        $admission->$field = $newValue;
                    }
                }
            }

            $admission->save();

            // If approved and payment is paid, create student and parents
            if ($request->application_status == 'Approved' && $request->payment_status == 'paid') {
                $standardLink_id = StandardLink::where([
                    ['school_id', Auth::user()->school_id],
                    ['standard_id', $admission->standard_id],
                    ['section_id', $admission->section_id]
                ])->first();

                $fee = null;
                if (class_exists('Gegok12\Fee\Models\Fee') && $request->fee_group_id) {
                    $fee = \Gegok12\Fee\Models\Fee::where([
                        ['school_id', Auth::user()->school_id],
                        ['fee_group_id', $request->fee_group_id]
                    ])->first();
                }

                if ($standardLink_id) {
                    $student = $this->CreateStudent($admission, 6, $standardLink_id->id, $admission->avatar, $fee, Auth::user());

                    // Create father account if mobile or email provided
                    if (!empty($admission->father_mobile_no) || !empty($admission->father_email)) {
                        $father = $this->CreateStudentFather($student->id, $admission, 7);
                        $father_data = [];
                        $father_data['application_no'] = $admission->application_no;
                        $father_data['school_name']    = ucwords($admission->school->name);
                        if ($father->email != null) {
                            $father_data['email'] = $father->email;
                            event(new AdmissionApprovalEvent($father_data));
                        }
                        if ($father->mobile_no != null) {
                            $father_data['mobile_no'] = $father->mobile_no;
                            $this->sendAdmissionApproval($father_data);
                        }
                    }

                    // Create mother account if mobile or email provided
                    if (!empty($admission->mother_mobile_no) || !empty($admission->mother_email)) {
                        $mother = $this->CreateStudentMother($student->id, $admission, 7);
                        $mother_data = [];
                        $mother_data['application_no'] = $admission->application_no;
                        $mother_data['school_name']    = ucwords($admission->school->name);
                        if ($mother->email != null) {
                            $mother_data['email'] = $mother->email;
                            event(new AdmissionApprovalEvent($mother_data));
                        }
                        if ($mother->mobile_no != null) {
                            $mother_data['mobile_no'] = $mother->mobile_no;
                            $this->sendAdmissionApproval($mother_data);
                        }
                    }
                }
            }

            $message = trans('messages.update_success_msg', ['module' => 'Admission']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $admission,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_UPDATE_ADMISSION_FORM,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try
        {
            $admission = Admission::where('id',$id)->first();

            $admission->delete();

            $message=trans('messages.delete_success_msg',['module' => 'Admission']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $admission,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_ADMISSION_FORM,
                $message
            ); 

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            dd($e->getMessage());
        } 
    }



    public function importApproved(Request $request)
    {
        try
        {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $studentEmailDomain = config('app.student_email_domain', 'student.local');
            $joiningDate = $request->joining_date ?? date('Y-m-d');

            $admissions = Admission::where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
                ['application_status', 'Approved']
            ])->where('payment_status', '!=', 'imported')
              ->whereNull('deleted_at')
              ->get();

            if ($admissions->count() == 0) {
                return response()->json(['error' => 'No approved admissions to import'], 400);
            }

            $studentsCreated = 0;
            $fathersCreated = 0;
            $mothersCreated = 0;
            $errors = [];

            foreach ($admissions as $admission) {
                try {
                    if (empty($admission->section_id)) {
                        $errors[] = $admission->name . ': No section assigned';
                        continue;
                    }

                    $standardLink = StandardLink::where([
                        ['school_id', $school_id],
                        ['standard_id', $admission->standard_id],
                        ['section_id', $admission->section_id]
                    ])->first();

                    if (!$standardLink) {
                        $errors[] = $admission->name . ': Class-Section combination not found';
                        continue;
                    }

                    $fee = null;
                    if (class_exists('Gegok12\Fee\Models\Fee') && $admission->fee_group_id) {
                        $fee = \Gegok12\Fee\Models\Fee::where([
                            ['school_id', $school_id],
                            ['fee_group_id', $admission->fee_group_id]
                        ])->first();
                    }

                    $student = $this->CreateStudent($admission, 6, $standardLink->id, $admission->avatar, $fee, Auth::user());

                    // Generate sequential registration number (max + 1)
                    $maxReg = \DB::table('users')
                        ->where('school_id', $school_id)
                        ->where('usergroup_id', 6)
                        ->where('id', '!=', $student->id)
                        ->max(\DB::raw('CAST(registration_number AS UNSIGNED)'));
                    $newReg = ($maxReg ? (int)$maxReg : 0) + 1;

                    // Update user: registration_number + auto-generated email
                    $student->registration_number = $newReg;
                    $student->email = $newReg . '@' . $studentEmailDomain;
                    $student->save();

                    // Update userprofile: registration_number + application_no in notes
                    \DB::table('userprofiles')
                        ->where('user_id', $student->id)
                        ->update([
                            'registration_number' => $newReg,
                            'notes' => 'Application: ' . $admission->application_no,
                            'joining_date' => $joiningDate,
                        ]);

                    // Update student_academics: roll_number and id_card_number
                    \DB::table('student_academics')
                        ->where('user_id', $student->id)
                        ->update([
                            'roll_number' => $newReg,
                            'id_card_number' => $newReg,
                        ]);

                    $studentsCreated++;

                    // Create father account
                    if (!empty($admission->father_mobile_no) || !empty($admission->father_email)) {
                        $this->CreateStudentFather($student->id, $admission, 7);
                        $fathersCreated++;
                    }

                    // Create mother account
                    if (!empty($admission->mother_mobile_no) || !empty($admission->mother_email)) {
                        $this->CreateStudentMother($student->id, $admission, 7);
                        $mothersCreated++;
                    }

                    // Mark as imported
                    $admission->payment_status = 'imported';
                    $admission->save();

                } catch (Exception $e) {
                    $errors[] = $admission->name . ': ' . $e->getMessage();
                    Log::error("Import admission #{$admission->id} failed: " . $e->getMessage());
                }
            }

            $message = "Import complete: {$studentsCreated} students, {$fathersCreated} fathers, {$mothersCreated} mothers created.";
            if (count($errors) > 0) {
                $message .= " Errors: " . implode('; ', $errors);
            }

            return response()->json(['success' => $message]);
        }
        catch (Exception $e)
        {
            Log::error("Import approved failed: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
