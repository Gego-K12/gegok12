<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Staff;

use App\Helpers\CustomFieldHelper;
use App\Helpers\SiteHelper;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\RegisterUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class StaffForm extends Component
{
    use Common;
    use LogActivity;
    use RegisterUser;
    use WithFileUploads;

    public const STAFF_USERGROUP_IDS = [8, 10, 11, 12, 13];

    public ?string $staffName = null;

    public ?int $userId = null;

    public ?string $name = null;

    public $firstname = '';

    public $lastname = '';

    public $mobile_no = '';

    public $email = '';

    public $gender = '';

    public $date_of_birth = '';

    public $blood_group = '';

    public $aadhar_number = '';

    public $employee_id = '';

    public $joining_date = '';

    public $designation = '';

    public $sub_designation = '';

    public $job_type = '';

    public $interested_in = '';

    public $marital_status = '';

    public $reporting_to = '';

    public $address = '';

    public $country_id = 7;

    public $state_id = '';

    public $city_id = '';

    public $pincode = '';

    public array $qualifications = [''];

    public $qualification_id = null;

    public $sub_qualification = '';

    public $ug_degree = '';

    public $pg_degree = '';

    public $specialization = '';

    public $notes = '';

    public $avatar = null;

    public $avatarPath = '';

    public $avatarDisplay = '';

    public array $custom_fields = [];

    public $customFields;

    public array $countrylist = [];

    public array $statelist = [];

    public array $citylist = [];

    public array $qualificationlist = [];

    public array $uglist = [];

    public array $pglist = [];

    public array $designationlist = [];

    public array $bloodGroups = [];

    public array $maritalList = [];

    public array $hodList = [];

    public array $principalList = [];

    public function mount($name = null)
    {
        $schoolId = Auth::user()->school_id;

        $this->countrylist = json_decode(json_encode(SiteHelper::getCountries()), true) ?? [];
        $this->statelist = json_decode(json_encode(SiteHelper::getStates()), true) ?? [];
        $this->citylist = json_decode(json_encode(SiteHelper::getCities()), true) ?? [];
        $this->qualificationlist = json_decode(json_encode(SiteHelper::getAdditionalCertificates()), true) ?? [];
        $this->uglist = json_decode(json_encode(SiteHelper::getUGList()), true) ?? [];
        $this->pglist = json_decode(json_encode(SiteHelper::getPGList()), true) ?? [];
        $this->designationlist = SiteHelper::getNonTeachingDesignations();
        $this->bloodGroups = SiteHelper::getBloodGroups();
        $this->maritalList = SiteHelper::getMaritalList();
        $this->hodList = json_decode(json_encode(SiteHelper::getHODList($schoolId)), true) ?? [];
        $this->principalList = json_decode(json_encode(SiteHelper::getPrincipalList($schoolId)), true) ?? [];
        $this->customFields = CustomFieldHelper::getFieldsForEntity('staff', $schoolId);

        if (! $name) {
            $this->date_of_birth = date('Y-m-d', strtotime('-25 years'));
            $this->employee_id = $this->nextEmployeeId($schoolId);

            return;
        }

        $user = User::where('name', $name)->firstOrFail();

        abort_unless(Gate::allows('member', $user), 403);

        $userprofile = Userprofile::where('user_id', $user->id)->first();
        $staffprofiles = TeacherProfile::where('user_id', $user->id)->get();
        $firstProfile = $staffprofiles->first();

        $this->staffName = $name;
        $this->userId = $user->id;

        $this->firstname = $userprofile->firstname;
        $this->lastname = $userprofile->lastname;
        $this->date_of_birth = $userprofile->date_of_birth ? date('Y-m-d', strtotime($userprofile->date_of_birth)) : '';
        $this->gender = $userprofile->gender;
        $this->blood_group = $userprofile->blood_group;
        $this->aadhar_number = $userprofile->aadhar_number;
        $this->address = $userprofile->address;
        $this->city_id = $userprofile->city_id;
        $this->state_id = $userprofile->state_id;
        $this->country_id = $userprofile->country_id;
        $this->pincode = $userprofile->pincode;
        $this->marital_status = $userprofile->marital_status;
        $this->notes = $userprofile->notes;
        $this->avatarPath = $userprofile->avatar;
        $this->avatarDisplay = $userprofile->AvatarPath;
        $this->joining_date = $userprofile->joining_date ? date('Y-m-d', strtotime($userprofile->joining_date)) : '';

        if ($firstProfile) {
            $this->employee_id = $firstProfile->employee_id;
            $this->designation = $firstProfile->designation;
            $this->sub_designation = $firstProfile->sub_designation;
            $this->sub_qualification = $firstProfile->sub_qualification;
            $this->ug_degree = $firstProfile->ug_degree;
            $this->pg_degree = $firstProfile->pg_degree;
            $this->specialization = $firstProfile->specialization;
            $this->job_type = $firstProfile->job_type;
            $this->interested_in = $firstProfile->interested_in;
            $this->reporting_to = $firstProfile->reporting_to;
        }

        if ($staffprofiles->isNotEmpty()) {
            $this->qualifications = $staffprofiles->pluck('qualification_id')->map(fn($id) => $id ?? '')->all();
        }

        $storedCustomFieldValues = CustomFieldHelper::getValues('staff', $user->id);

        foreach ($this->customFields as $field) {
            $stored = $storedCustomFieldValues->get($field->id);
            $value = $stored?->value;
            $this->custom_fields[$field->id] = ($field->field_type === 'checkbox' && $value) ? explode(',', $value) : $value;
        }
    }

    protected function nextEmployeeId(int $schoolId): string
    {
        $latest = TeacherProfile::where('school_id', $schoolId)
            ->whereNotNull('employee_id')
            ->orderByDesc('id')
            ->first();

        if (! $latest) {
            return 'EMP001';
        }

        $employeeId = $latest->employee_id;

        return ++$employeeId;
    }

    public function isEdit(): bool
    {
        return $this->userId !== null;
    }

    public function updatedAvatar(): void
    {
        if (! $this->avatar instanceof TemporaryUploadedFile) {
            return;
        }

        $this->validate(['avatar' => 'mimes:jpg,jpeg,png'], [], ['avatar' => 'Avatar']);

        $folder = Auth::user()->school->slug . '/uploads/admin/teacher/avatar';
        $path = $this->uploadFile($folder, $this->avatar);

        if ($path === '') {
            $this->addError('avatar', 'The photo could not be saved — please try selecting it again.');

            return;
        }

        $this->avatarPath = $path;
        $this->avatarDisplay = $this->getFilePath($this->avatarPath);
    }

    public function addQualificationRow(): void
    {
        $this->qualifications[] = '';
    }

    public function removeQualificationRow(int $index): void
    {
        unset($this->qualifications[$index]);
        $this->qualifications = array_values($this->qualifications);
    }

    public function hasSubDesignation(): bool
    {
        return $this->designation === 'others';
    }

    public function hasReportingTo(): bool
    {
        return $this->designation !== '' && ! in_array($this->designation, ['principal', 'vice_principal']);
    }

    protected function usergroupId(): int
    {
        return match ($this->designation) {
            'librarian' => 8,
            'receptionist' => 10,
            'accountant' => 11,
            'stock_keeper' => 12,
            default => 13,
        };
    }

    protected function rules(): array
    {
        $schoolId = Auth::user()->school_id;
        $nameRegex = $this->isEdit() ? 'alpha' : 'regex:/^[A-Za-z\s]+$/';
        $punctuationRegex = 'regex:/^[A-Za-z_~\-!@#$%^&*.,:()\s]+$/';

        $aadharRule = Rule::unique('userprofiles', 'aadhar_number')->where(fn($query) => $query->where('school_id', $schoolId)->whereIn('usergroup_id', array_merge([5], self::STAFF_USERGROUP_IDS)));
        if ($this->isEdit()) {
            $aadharRule = $aadharRule->ignore($this->userId, 'user_id');
        }

        $employeeIdRule = Rule::unique('teacherprofile', 'employee_id')->where(fn($query) => $query->where('school_id', $schoolId));
        if ($this->isEdit()) {
            $employeeIdRule = $employeeIdRule->ignore($this->userId, 'user_id');
        }

        $rules = [
            'firstname' => ['required', $nameRegex, 'max:15'],
            'lastname' => ['nullable', $nameRegex, 'max:15'],
            'date_of_birth' => ['required', 'date', function ($attribute, $value, $fail) {
                if ($value > date('Y-m-d') || $value < '1940-01-01') {
                    $fail('Enter a valid date of birth.');
                }
            }],
            'gender' => 'required',
            'blood_group' => 'required',
            'aadhar_number' => ['nullable', 'numeric', 'digits:12', $aadharRule],
            'employee_id' => ['required', 'alpha_num', $employeeIdRule],
            'designation' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'notes' => ['nullable', 'regex:/^[A-Za-z0-9_~\-!@#$%^&*.,:()\s]+$/'],
            'avatar' => 'nullable|mimes:jpg,jpeg,png',
        ];

        if ($this->isEdit()) {
            $rules['joining_date'] = ['required', 'date', function ($attribute, $value, $fail) {
                $earliestYear = now()->subYears(60)->format('Y');
                if ($value > date('Y-m-d') || date('Y', strtotime($value)) < $earliestYear) {
                    $fail('Select a valid joining date.');
                }
            }];
            $rules['pincode'] = 'nullable|numeric|digits:6';

            if ($this->hasReportingTo()) {
                $rules['reporting_to'] = 'required';
            }
        } else {
            $rules['mobile_no'] = ['required', 'numeric', 'digits:10', Rule::unique('users', 'mobile_no')];
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')->where(fn($query) => $query->where('school_id', $schoolId))];
            $rules['joining_date'] = ['required', 'date', function ($attribute, $value, $fail) {
                $earliestYear = now()->subYears(40)->format('Y');
                if ($value > date('Y-m-d') || date('Y', strtotime($value)) < $earliestYear) {
                    $fail('Select a valid joining date.');
                }
            }];
            $rules['pincode'] = 'required|numeric|digits:6';
            $rules['job_type'] = 'required';
            $rules['marital_status'] = 'required';
        }

        $rules = array_merge($rules, CustomFieldHelper::validationRules('staff', $schoolId));

        if ($this->hasSubDesignation()) {
            $rules['sub_designation'] = ['required', $punctuationRegex];
        }

        if ($this->ug_degree || $this->pg_degree) {
            $rules['specialization'] = ['required', $punctuationRegex];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'firstname.required' => 'First Name Is Required',
            'firstname.regex' => 'Enter A Valid First Name',
            'firstname.alpha' => 'Enter A Valid First Name',
            'firstname.max' => 'First Name Should Be Atmost 15 Characters',
            'lastname.regex' => 'Enter A Valid Last Name',
            'lastname.alpha' => 'Enter A Valid Last Name',
            'lastname.max' => 'Last Name Should Be Atmost 15 Characters',
            'mobile_no.required' => 'Mobile Number Is Required',
            'mobile_no.digits' => 'Mobile Number Should Be 10 Digits',
            'mobile_no.unique' => 'Mobile Number Already In Use. Enter Different Mobile Number',
            'email.required' => 'Email ID Is Required',
            'email.email' => 'Enter A Valid Email ID',
            'email.unique' => 'Email ID Already In Use. Enter Different Email ID',
            'gender.required' => 'Gender Is Required',
            'blood_group.required' => 'Blood Group Is Required',
            'aadhar_number.digits' => 'Aadhaar Number Should Be Of 12 Digits',
            'aadhar_number.unique' => 'Aadhaar Number Already In Use. Enter Different Aadhaar Number',
            'employee_id.required' => 'Employee ID Is Required',
            'employee_id.alpha_num' => 'Employee ID Should Be Alphanumeric',
            'employee_id.unique' => 'Employee ID Already Exists',
            'joining_date.required' => 'Joining Date Is Required',
            'designation.required' => 'Designation Is Required',
            'sub_designation.required' => 'Sub Designation Is Required',
            'sub_designation.regex' => 'Enter A Valid Sub Designation',
            'reporting_to.required' => 'Report To Is Required',
            'country_id.required' => 'Country Is Required',
            'state_id.required' => 'State Is Required',
            'city_id.required' => 'City Is Required',
            'pincode.required' => 'Pincode Is Required',
            'pincode.digits' => 'Pincode Should Be 6 Digits',
            'job_type.required' => 'Job Type Is Required',
            'marital_status.required' => 'Marital Status Is Required',
            'specialization.required' => 'Specialization Is Required',
            'specialization.regex' => 'Enter A Valid Specialization',
            'notes.regex' => 'Enter Valid Notes',
            'avatar.mimes' => 'Choose jpg,jpeg,png File',
        ];
    }

    protected function validationAttributes(): array
    {
        return CustomFieldHelper::validationAttributes('staff', Auth::user()->school_id);
    }

    public function save()
    {
        $this->validate();

        $schoolId = Auth::user()->school_id;
        $academicYear = SiteHelper::getAcademicYear($schoolId);
        $usergroupId = $this->usergroupId();

        $qualifications = array_values(array_filter($this->qualifications, fn($q) => $q !== null && $q !== ''));
        $this->qualification_id = $qualifications !== [] ? $qualifications : null;

        // ug_degree/pg_degree/reporting_to are foreign keys — an empty
        // string (rather than null) fails the constraint when left unset.
        foreach (['ug_degree', 'pg_degree', 'reporting_to', 'sub_designation', 'sub_qualification', 'interested_in'] as $optionalField) {
            if ($this->{$optionalField} === '') {
                $this->{$optionalField} = null;
            }
        }

        $path = $this->avatarPath ?? '';

        if ($this->isEdit()) {
            $userprofile = $this->UpdateTeacher($this, $schoolId, $academicYear, $this->userId, $path);

            $this->persistCustomFields($this->userId, $schoolId);

            $message = trans('messages.update_success_msg', ['module' => 'Staff']);

            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $this->getRequestIP(), 'details' => $_SERVER['HTTP_USER_AGENT'] ?? ''],
                LOGNAME_EDIT_TEACHER,
                $message
            );

            session()->put('successmessage', $message);

            $this->redirect(url('/admin/staff/edit/' . $this->staffName), navigate: false);

            return;
        }

        $user = $this->CreateTeacher($this, $schoolId, $academicYear, $path, $usergroupId);

        $this->persistCustomFields($user->id, $schoolId);

        $message = trans('messages.add_success_msg', ['module' => 'Staff']);

        $this->doActivityLog(
            $user,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => $_SERVER['HTTP_USER_AGENT'] ?? ''],
            LOGNAME_ADD_TEACHER,
            $message
        );

        session()->put('successmessage', $message);

        $this->redirect(url('/admin/staffs'), navigate: false);
    }

    protected function persistCustomFields(int $userId, int $schoolId): void
    {
        foreach (CustomFieldHelper::getFieldsForEntity('staff', $schoolId) as $field) {
            $value = $this->custom_fields[$field->id] ?? null;

            if ($value === null || $value === '') {
                continue;
            }

            $value = is_array($value) ? implode(',', $value) : $value;

            CustomFieldHelper::setValue($field->id, 'staff', $userId, $schoolId, $value);
        }
    }

    public function render()
    {
        return view('livewire.admin.staff.staff-form');
    }
}
