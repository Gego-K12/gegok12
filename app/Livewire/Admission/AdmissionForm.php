<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admission;

use App\Helpers\CustomFieldHelper;
use App\Helpers\SiteHelper;
use App\Models\Admission;
use App\Models\School;
use App\Models\Standard;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdmissionForm extends Component
{
    use Common;
    use LogActivity;
    use WithFileUploads;

    public string $slug;

    public int $schoolId;

    public int $currentStep = 1;

    public array $standardlist = [];

    public array $bloodGroupList = [];

    public array $qualificationList = [];

    public array $transportList = [];

    public array $boardList = [
        ['id' => 'anglo-indian', 'name' => 'Anglo Indian'],
        ['id' => 'cbse', 'name' => 'CBSE'],
        ['id' => 'icse', 'name' => 'ICSE'],
        ['id' => 'matric', 'name' => 'Matric'],
        ['id' => 'state-board', 'name' => 'State Board'],
        ['id' => 'others', 'name' => 'Others'],
    ];





    public array $languageList = [
        ['id' => 'tamil', 'name' => 'Tamil'],
        ['id' => 'hindi', 'name' => 'Hindi'],
        ['id' => 'sanskrit', 'name' => 'Sanskrit'],
        ['id' => 'french', 'name' => 'French'],
    ];

    public array $groupList = [
        ['id' => 'group1', 'name' => 'Group 1 : Maths, Physics, Chemistry & Computer Science'],
        ['id' => 'group2', 'name' => 'Group 2 : Maths, Physics, Chemistry & Biology'],
        ['id' => 'group3', 'name' => 'Group 3 : Physics, Chemistry, Biology & Computer Science'],
        ['id' => 'group4', 'name' => 'Group 4 : Commerce, Accountancy, Economics & Business Maths'],
        ['id' => 'group5', 'name' => 'Group 5 : Commerce, Accountancy, Economics & Computer Science'],
    ];

    public array $booleanList = [
        ['id' => 'yes', 'name' => 'Yes'],
        ['id' => 'no', 'name' => 'No'],
    ];

    public array $medicalList = [
        ['id' => 'Asthama', 'name' => 'Asthama'],
        ['id' => 'Diabetics', 'name' => 'Diabetics'],
        ['id' => 'Dizziness', 'name' => 'Fainting / Dizziness'],
        ['id' => 'Fits', 'name' => 'Fits'],
        ['id' => 'Hear Problem', 'name' => 'Hear Problem'],
        ['id' => 'Intellectual Disability', 'name' => 'Intellectual Disability'],
        ['id' => 'Migraines', 'name' => 'Migraines'],
        ['id' => 'Recent Injuries', 'name' => 'Recent Injuries'],
        ['id' => 'Others', 'name' => 'Others'],
    ];

    public array $activitiesList = [
        ['id' => 'Art & Craft', 'name' => 'Art & Craft'],
        ['id' => 'Bharatham', 'name' => 'Bharatham'],
        ['id' => 'Chess', 'name' => 'Chess'],
        ['id' => 'Drums', 'name' => 'Drums'],
        ['id' => 'Embroidery', 'name' => 'Embroidery'],
        ['id' => 'Guitar', 'name' => 'Guitar'],
        ['id' => 'Karate', 'name' => 'Karate'],
        ['id' => 'Keyboard', 'name' => 'Keyboard'],
        ['id' => 'Mridangam', 'name' => 'Mridangam'],
        ['id' => 'Skating', 'name' => 'Skating'],
        ['id' => 'Vocal', 'name' => 'Vocal'],
        ['id' => 'Violin', 'name' => 'Violin'],
    ];

    // Step 1
    public string $standard_id = '';

    // Step 2
    public string $name = '';

    public string $lastname = '';

    public string $date_of_birth = '';

    public string $gender = '';

    public string $height = '';

    public string $weight = '';

    public $avatar = null;

    public string $birth_place = '';

    public string $nationality = '';

    public string $religion = '';

    public string $community = '';

    public string $mother_tongue = '';

    public string $identification_marks = '';

    public string $aadhar_number = '';

    public string $blood_group = '';

    public string $school_last_studied = '';

    public string $reason_for_leaving = '';

    public string $permanent_address = '';

    public string $address_for_communication = '';

    public string $siblings = '';

    public string $siblings_details = '';

    // Step 3
    public string $english = '';

    public string $tamil = '';

    public string $maths = '';

    public string $science = '';

    public string $social = '';

    public string $board_of_education = '';

    public string $choice_of_language = '';

    public string $group_selection = '';

    public string $board_registration_number = '';

    // Step 4
    public string $father_name = '';

    public string $father_qualification_id = '';

    public string $father_occupation = '';

    public string $father_designation = '';

    public string $father_organisation = '';

    public string $father_income = '';

    public string $father_mobile_no = '';

    public string $father_email = '';

    public string $father_aadhar_number = '';

    public $father_avatar = null;

    public string $mother_name = '';

    public string $mother_qualification_id = '';

    public string $mother_occupation = '';

    public string $mother_designation = '';

    public string $mother_organisation = '';

    public string $mother_income = '';

    public string $mother_mobile_no = '';

    public string $mother_email = '';

    public string $mother_aadhar_number = '';

    public $mother_avatar = null;

    public string $emergency_contact_1 = '';

    public string $relation_with_student_1 = '';

    public string $emergency_contact_2 = '';

    public string $relation_with_student_2 = '';

    // Step 5
    public string $medical_history = '';

    public array $medical_details = [];

    public string $extra_curricular_activities = '';

    public array $activities = [];

    public string $mode_of_transport = '';

    public string $driver_name = '';

    public string $driver_mobile_number = '';

    // Step 6: Additional Info
    public $customFields;

    public array $custom_fields = [];

    public bool $submitted = false;

    public ?string $submitError = null;

    public ?string $successMessage = null;

    public function mount(string $slug)
    {
        $school = School::where('slug', $slug)->firstOrFail();

        $this->slug = $slug;
        $this->schoolId = $school->id;

        $this->standardlist = json_decode(json_encode(SiteHelper::getStandardList($school->id)), true) ?? [];
        $this->bloodGroupList = SiteHelper::getBloodGroups();
        $this->qualificationList = json_decode(json_encode(SiteHelper::getQualifications()), true) ?? [];
        $this->transportList = SiteHelper::getTransportList();

        $this->customFields = CustomFieldHelper::getFieldsForEntity('admission', $school->id);

       // dd($this->customFields);

        // Livewire only binds a checkbox input as part of an array when the
        // underlying model is already an array -- a checkbox field with just
        // one option would otherwise bind as a plain boolean and fail the
        // 'array' validation rule (see the same fix in StudentForm).
        foreach ($this->customFields as $field) {
            if ($field->field_type === 'checkbox') {
                $this->custom_fields[$field->id] = [];
            }
        }
    }

    protected function rules(): array
    {
        return match ($this->currentStep) {
            1 => [
                'standard_id' => 'required',
            ],
            2 => $this->studentRules(),
            3 => $this->academicRules(),
            4 => [
                'father_name' => 'required|regex:/^[A-Za-z\s]+$/',
                'father_qualification_id' => 'required',
                'father_occupation' => 'required|regex:/^[A-Za-z\s]+$/',
                'father_designation' => 'required|regex:/^[A-Za-z\s]+$/',
                'father_organisation' => 'required|regex:/^[A-Za-z\s]+$/',
                'father_income' => 'required|numeric|regex:/^[0-9]{1,9}$/',
                'father_mobile_no' => 'required|numeric|digits:10',
                'father_email' => 'required|email',
                //'father_aadhar_number' => 'required|numeric|digits:12',
                'father_avatar' => 'nullable|image|max:2048',

                'mother_name' => 'required|regex:/^[A-Za-z\s]+$/',
                'mother_qualification_id' => 'required',
                'mother_occupation' => 'required|regex:/^[A-Za-z\s]+$/',
                'mother_designation' => 'required|regex:/^[A-Za-z\s]+$/',
                'mother_organisation' => 'required|regex:/^[A-Za-z\s]+$/',
                'mother_income' => 'required|numeric|regex:/^[0-9]{1,9}$/',
                'mother_mobile_no' => 'nullable|numeric|digits:10',
                'mother_email' => 'nullable|email',
                //'mother_aadhar_number' => 'required|numeric|digits:12',
                'mother_avatar' => 'nullable|image|max:2048',

                'emergency_contact_1' => 'required|numeric|digits:10',
                'relation_with_student_1' => 'required|regex:/^[A-Za-z\s]+$/',
                'emergency_contact_2' => 'required|numeric|digits:10',
                'relation_with_student_2' => 'required|regex:/^[A-Za-z\s]+$/',
            ],
            5 => $this->personalRules(),
            6 => CustomFieldHelper::validationRules('admission', $this->schoolId),
            default => [],
        };
    }

    protected function studentRules(): array
    {
        $rules = [
            'name' => 'required|regex:/^[A-Za-z\s]+$/',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'height' => 'required|numeric|digits:3',
            'weight' => 'required|numeric|min:1|max:150',
            'avatar' => 'nullable|image|max:2048',
            'birth_place' => 'required|regex:/^[A-Za-z\s]+$/',
            'nationality' => 'required|regex:/^[A-Za-z\s]+$/',
           // 'religion' => 'required|regex:/^[A-Za-z\s]+$/',
            //'community' => 'required|regex:/^[A-Za-z\s]+$/',
            'mother_tongue' => 'required|regex:/^[A-Za-z\s]+$/',
            'identification_marks' => 'required|regex:/^[A-Za-z\s]+$/',
            //'aadhar_number' => 'required|digits:12',
            'blood_group' => 'required',
            'school_last_studied' => 'nullable|regex:/^[A-Za-z\s]+$/',
            'reason_for_leaving' => 'nullable|regex:/^[A-Za-z\s]+$/',
            'permanent_address' => 'required',
            'address_for_communication' => 'required',
            'siblings' => 'required',
        ];

        if ($this->siblings === 'yes') {
            $rules['siblings_details'] = 'required|string|max:255';
        }

        return $rules;
    }

    protected function academicRules(): array
    {
        $rules = [
            'english' => 'nullable|numeric|max:100',
            'tamil' => 'nullable|numeric|max:100',
            'maths' => 'nullable|numeric|max:100',
            'science' => 'nullable|numeric|max:100',
            'social' => 'nullable|numeric|max:100',
            //'board_of_education' => 'required',
            //'choice_of_language' => 'required',
            'group_selection' => 'nullable',
        ];

        $standardName = optional(Standard::find($this->standard_id))->name;

        if (in_array($standardName, ['10', '11', '12'])) {
            $rules['group_selection'] = 'required';
            $rules['board_registration_number'] = 'required|numeric';
        }

        return $rules;
    }

    protected function personalRules(): array
    {
        $rules = [
            'medical_history' => 'required',
            'extra_curricular_activities' => 'required',
            'mode_of_transport' => 'required',
        ];

        if (in_array($this->mode_of_transport, ['auto', 'rickshaw', 'taxi'])) {
            $rules['driver_name'] = 'required|regex:/^[A-Za-z\s]+$/';
            $rules['driver_mobile_number'] = 'required|numeric|digits:10';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'standard_id.required' => 'Class Is Required',

            'name.required' => 'Name Required',
            'name.regex' => 'Enter Valid Name',
            'date_of_birth.required' => 'Date Of Birth Required',
            'gender.required' => 'Select Gender',
            'height.required' => 'Height Required',
            'height.numeric' => 'Enter Valid Number',
            'height.digits' => 'Enter Valid Height',
            'weight.required' => 'Weight Required',
            'weight.numeric' => 'Enter Valid Number',
            'weight.min' => 'Weight Must Be Atleast 1',
            'weight.max' => 'Weight Cannot Be More Than 150',
            'avatar.image' => 'Photo Must Be An Image',
            'avatar.max' => 'Photo Must Not Be Larger Than 2MB',
            'birth_place.required' => 'Birth Place Required',
            'birth_place.regex' => 'Enter Valid Birth Place',
            'nationality.required' => 'Nationality Required',
            'nationality.regex' => 'Enter Valid Nationality',
            'religion.required' => 'Religion Required',
            'religion.regex' => 'Enter Valid Religion',
            'community.required' => 'Community Required',
            'community.regex' => 'Enter Valid Community Required',
            'mother_tongue.required' => 'Mother Tongue Required',
            'mother_tongue.regex' => 'Enter Valid Mother Tongue',
            'identification_marks.required' => 'Identification Mark Required',
            'identification_marks.regex' => 'Enter Valid Identification Mark',
            'aadhar_number.required' => 'Aadhaar number Required',
            'aadhar_number.digits' => 'Enter Valid Aadhaar Number',
            'blood_group.required' => 'Blood Group Required',
            'school_last_studied.regex' => 'Enter Valid school last studied',
            'reason_for_leaving.regex' => 'Enter Valid Reason For Leaving',
            'permanent_address.required' => 'Permanent Address Required',
            'address_for_communication.required' => 'Communication Address Required',
            'siblings.required' => 'Select Siblings',
            'siblings_details.required' => 'Enter Sibling Details',

            'english.numeric' => 'Enter Valid English Marks',
            'english.max' => 'Enter Valid English Marks Cannot Be Greater Than 100',
            'tamil.numeric' => 'Enter Valid Tamil Marks',
            'tamil.max' => 'Enter Valid Tamil Marks Cannot Be Greater Than 100',
            'maths.numeric' => 'Enter Valid Maths Marks',
            'maths.max' => 'Enter Valid Maths Marks Cannot Be Greater Than 100',
            'science.numeric' => 'Enter Valid Science Marks',
            'science.max' => 'Enter Valid Science Marks Cannot Be Greater Than 100',
            'social.numeric' => 'Enter Valid Social Marks',
            'social.max' => 'Enter Valid Social Marks Cannot Be Greater Than 100',
            //'board_of_education.required' => 'Board of Study Is Required',
            'choice_of_language.required' => 'Choice of Language Is Required',
            'group_selection.required' => 'Group Selection Is Required',
            'board_registration_number.required' => 'Board Registration Number Is Required',
            'board_registration_number.numeric' => 'Board Registration Number Should Be Numeric',

            'father_name.required' => 'Father Name Is Required',
            'father_name.regex' => 'Enter Valid Father Name',
            'father_qualification_id.required' => 'Father Qualification Is Required',
            'father_designation.required' => 'Designation Is Required',
            'father_designation.regex' => 'Enter Valid Designation',
            'father_occupation.required' => 'Occupation Is Required',
            'father_occupation.regex' => 'Enter Valid Occupation',
            'father_organisation.required' => 'Organisation Name Is Required',
            'father_organisation.regex' => 'Enter Valid Organisation Name',
            'father_income.required' => 'Income Is Required',
            'father_income.numeric' => 'Income Should Be In Numbers',
            'father_income.regex' => 'Enter A Valid Income (up to 9 digits)',
            'father_mobile_no.required' => 'Mobile Number Is Required',
            'father_mobile_no.numeric' => 'Enter Valid Mobile Number',
            'father_mobile_no.digits' => 'Mobile Number Should Be Of 10 Digits',
            'father_email.required' => 'Email Is Required',
            'father_email.email' => 'Enter Valid Email',
            //'father_aadhar_number.required' => 'Aadhaar Number Is Required',
            //'father_aadhar_number.numeric' => 'Enter Valid Aadhaar Number',
            //'father_aadhar_number.digits' => 'Aadhaar Number Should Be Of 12 Digits',
            'father_avatar.image' => 'Photo Must Be An Image',
            'father_avatar.max' => 'Photo Must Not Be Larger Than 2MB',

            'mother_name.required' => 'Mother Name Is Required',
            'mother_name.regex' => 'Enter Valid Mother Name',
            'mother_qualification_id.required' => 'Mother Qualification Is Required',
            'mother_designation.required' => 'Designation Is Required',
            'mother_designation.regex' => 'Enter Valid Designation',
            'mother_occupation.required' => 'Occupation Is Required',
            'mother_occupation.regex' => 'Enter Valid Occupation',
            'mother_organisation.required' => 'Organisation Name Is Required',
            'mother_organisation.regex' => 'Enter Valid Organisation Name',
            'mother_income.required' => 'Income Is Required',
            'mother_income.numeric' => 'Income Should Be In Numbers',
            'mother_income.regex' => 'Enter A Valid Income (up to 9 digits)',
            'mother_mobile_no.numeric' => 'Enter Valid Mobile Number',
            'mother_mobile_no.digits' => 'Mobile Number Should Be Of 10 Digits',
            'mother_email.email' => 'Enter Valid Email',
            //'mother_aadhar_number.required' => 'Aadhaar Number Is Required',
            //'mother_aadhar_number.numeric' => 'Enter Valid Aadhaar Number',
            //'mother_aadhar_number.digits' => 'Aadhaar Number Should Be Of 12 Digits',
            'mother_avatar.image' => 'Photo Must Be An Image',
            'mother_avatar.max' => 'Photo Must Not Be Larger Than 2MB',

            'relation_with_student_1.required' => 'Relationship Is Required',
            'relation_with_student_1.regex' => 'Enter Valid Relation',
            'relation_with_student_2.required' => 'Relationship Is Required',
            'relation_with_student_2.regex' => 'Enter Valid Relation',
            'emergency_contact_1.required' => 'Mobile Number Is Required',
            'emergency_contact_1.numeric' => 'Enter Valid Mobile Number',
            'emergency_contact_1.digits' => 'Mobile Number Should Be Of 10 Digits',
            'emergency_contact_2.required' => 'Mobile Number Is Required',
            'emergency_contact_2.numeric' => 'Enter Valid Mobile Number',
            'emergency_contact_2.digits' => 'Mobile Number Should Be Of 10 Digits',

            'medical_history.required' => 'Medical History Is Required',
            'extra_curricular_activities.required' => 'Extra Curricular Activities Is Required',
            'mode_of_transport.required' => 'Mode Of Trasnport Is Required',
            'driver_name.required' => 'Driver Name Required',
            'driver_name.regex' => 'Enter Valid Driver Name',
            'driver_mobile_number.required' => 'Driver Mobile No. Required',
            'driver_mobile_number.numeric' => 'Enter Valid Mobile Number',
            'driver_mobile_number.digits' => 'Mobile Number Should Be 10 Digits',
        ];
    }

    protected function validationAttributes(): array
    {
        return CustomFieldHelper::validationAttributes('admission', $this->schoolId);
    }

    public function nextStep()
    {
        $this->validate($this->rules());
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->resetErrorBag();
        $this->currentStep--;
    }

    public function toggleGroupSelection(string $value)
    {
        $this->group_selection = $this->group_selection === $value ? '' : $value;
    }

    public function resetForm()
    {
        $slug = $this->slug;
        $schoolId = $this->schoolId;
        $standardlist = $this->standardlist;
        $bloodGroupList = $this->bloodGroupList;
        $qualificationList = $this->qualificationList;
        $transportList = $this->transportList;
        $customFields = $this->customFields;

        $this->reset();

        $this->slug = $slug;
        $this->schoolId = $schoolId;
        $this->standardlist = $standardlist;
        $this->bloodGroupList = $bloodGroupList;
        $this->qualificationList = $qualificationList;
        $this->transportList = $transportList;
        $this->customFields = $customFields;
        $this->currentStep = 1;

        foreach ($this->customFields as $field) {
            if ($field->field_type === 'checkbox') {
                $this->custom_fields[$field->id] = [];
            }
        }
    }

    public function submit()
    {
        $this->validate($this->rules());

        $school = School::findOrFail($this->schoolId);
        $academicYear = SiteHelper::getAcademicYear($school->id);
        $admin = User::where('school_id', $school->id)->ByRole(3)->first();

        try {
            $admission = new Admission;

            $admission->school_id = $school->id;
            $admission->academic_year_id = $academicYear->id;
            $admission->standard_id = $this->standard_id;
            $admission->name = $this->name;
            $admission->date_of_birth = $this->date_of_birth;

            if ($this->avatar) {
                $admission->avatar = $this->uploadFile($school->id . '/student/avatar', $this->avatar);
            }

            $admission->gender = $this->gender;
            $admission->height = $this->height;
            $admission->weight = $this->weight;
            $admission->birth_place = $this->birth_place;
            $admission->nationality = $this->nationality;
            //$admission->religion = $this->religion;
           // $admission->community = $this->community;
            $admission->mother_tongue = $this->mother_tongue;
            $admission->identification_marks = $this->identification_marks;
           // $admission->aadhar_number = $this->aadhar_number;
            $admission->blood_group = $this->blood_group;
            $admission->school_last_studied = $this->school_last_studied;
            $admission->reason_for_leaving = $this->reason_for_leaving;
            $admission->permanent_address = $this->permanent_address;
            $admission->address_for_communication = $this->address_for_communication;
            $admission->siblings = $this->siblings;
            $admission->siblings_details = $this->siblings === 'yes' ? $this->siblings_details : null;

            $admission->half_yearly_mark_details = json_encode([
                'english' => $this->english,
                'tamil' => $this->tamil,
                'maths' => $this->maths,
                'science' => $this->science,
                'social' => $this->social,
            ]);

            //$admission->board_of_education = $this->board_of_education;
            //$admission->choice_of_language = $this->choice_of_language;
            $admission->group_selection = $this->group_selection;
            $admission->board_registration_number = $this->board_registration_number;

            $admission->father_name = $this->father_name;
            $admission->father_qualification_id = $this->father_qualification_id;
            $admission->father_designation = $this->father_designation;
            $admission->father_occupation = $this->father_occupation;
            $admission->father_organisation = $this->father_organisation;
            $admission->father_income = $this->father_income;
            $admission->father_mobile_no = $this->father_mobile_no;
            $admission->father_email = $this->father_email;
            $admission->father_aadhar_number = $this->father_aadhar_number;

            if ($this->father_avatar) {
                $admission->father_avatar = $this->uploadFile($school->id . '/student/avatar', $this->father_avatar);
            }

            if ($this->mother_avatar) {
                $admission->mother_avatar = $this->uploadFile($school->id . '/student/avatar', $this->mother_avatar);
            }

            $admission->mother_name = $this->mother_name;
            $admission->mother_qualification_id = $this->mother_qualification_id;
            $admission->mother_designation = $this->mother_designation;
            $admission->mother_occupation = $this->mother_occupation;
            $admission->mother_organisation = $this->mother_organisation;
            $admission->mother_income = $this->mother_income;
            $admission->mother_mobile_no = $this->mother_mobile_no;
            $admission->mother_email = $this->mother_email;
            $admission->mother_aadhar_number = $this->mother_aadhar_number;

            $admission->emergency_contact_1 = $this->emergency_contact_1;
            $admission->relation_with_student_1 = $this->relation_with_student_1;
            $admission->emergency_contact_2 = $this->emergency_contact_2;
            $admission->relation_with_student_2 = $this->relation_with_student_2;

            $admission->medical_history = $this->medical_history;
            $admission->medical_details = implode(',', $this->medical_details);
            $admission->extra_curricular_activities = $this->extra_curricular_activities;
            $admission->activities = implode(',', $this->activities);
            $admission->mode_of_transport = $this->mode_of_transport;

            if (in_array($this->mode_of_transport, ['auto', 'rickshaw', 'taxi'])) {
                $admission->transport_details = json_encode([
                    'driver_name' => $this->driver_name,
                    'driver_mobile_number' => $this->driver_mobile_number,
                ]);
            }

            $admission->application_status = 'Draft';
            $admission->application_no = 'APP-FORM-' . date('YmdHis');

            $customFieldValues = [];

            foreach ($this->customFields as $field) {
                $value = $this->custom_fields[$field->id] ?? null;

                if ($field->field_type === 'file') {
                    if ($value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                        $customFieldValues[$field->id] = $this->uploadFile($school->id . '/custom-fields', $value);
                    }

                    continue;
                }

                if ($value === null || $value === '' || $value === []) {
                    continue;
                }

                $customFieldValues[$field->id] = is_array($value) ? implode(',', $value) : $value;
            }

            $admission->custom_fields = json_encode($customFieldValues);

            $admission->save();

            $message = trans('messages.add_success_msg', ['module' => 'Admission Form']);

            $this->doActivityLog(
                $admission,
                $admin,
                ['ip' => $this->getRequestIP()],
                LOGNAME_ADD_ADMISSION_FORM,
                $message
            );

            $this->resetForm();
            $this->submitted = true;
            $this->successMessage = $message;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->submitError = 'Something went wrong while submitting the form. Please try again.';
        }
    }

    public function render()
    {
        return view('livewire.admission.admission-form');
    }
}
