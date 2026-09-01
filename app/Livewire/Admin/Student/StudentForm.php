<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Student;

use App\Helpers\CustomFieldHelper;
use App\Helpers\SiteHelper;
use App\Models\Standard;
use App\Models\StandardLink;
use App\Models\Userprofile;
use App\Models\Users\StudentUser;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\RegisterUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class StudentForm extends Component
{
    use Common;
    use LogActivity;
    use RegisterUser;
    use WithFileUploads;

    public ?string $studentName = null;

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

    public $address = '';

    public $country_id = 7;

    public $state_id = '';

    public $city_id = '';

    public $pincode = '';

    public $birth_place = '';

    public $native_place = '';

    public $mother_tongue = '';

    public $caste = '';

    public $sub_caste = '';

    public $mode_of_transport = '';

    public $driver_name = '';

    public $driver_contact_number = '';

    public $notes = '';

    public $avatar = null;

    public $avatarPath = '';

    public $avatarDisplay = '';

    public $registration_number = '';

    public $EMIS_number = '';

    public $joining_date = '';

    public $standard = '';

    public $roll_number = '';

    public $id_card_number = '';

    public $board_registration_number = '';

    public array $custom_fields = [];

    public array $existingCustomFieldFiles = [];

    public array $countrylist = [];

    public array $statelist = [];

    public array $citylist = [];

    public $standardLinklist;

    public array $bloodGroups = [];

    public array $casteList = [];

    public array $transportList = [];

    public $customFields;

    public function mount($name = null)
    {
        $schoolId = Auth::user()->school_id;

        $this->countrylist = json_decode(json_encode(SiteHelper::getCountries()), true) ?? [];
        $this->statelist = json_decode(json_encode(SiteHelper::getStates()), true) ?? [];
        $this->citylist = json_decode(json_encode(SiteHelper::getCities()), true) ?? [];
        $this->standardLinklist = json_decode(json_encode(SiteHelper::getStandardLinkList($schoolId)), true) ?? [];
        $this->bloodGroups = SiteHelper::getBloodGroups();
        $this->casteList = SiteHelper::getCasteList();
        $this->transportList = SiteHelper::getTransportList();
        $this->customFields = CustomFieldHelper::getFieldsForEntity('student', $schoolId);

        if (! $name) {
            $this->date_of_birth = date('Y-m-d', strtotime('-4 years'));
            $this->joining_date = date('Y-m-d');

            return;
        }

        $user = StudentUser::where('name', $name)->firstOrFail();

        abort_unless(Gate::allows('member', $user), 403);

        $userprofile = Userprofile::where('user_id', $user->id)->first();
        $studentAcademic = $user->studentAcademicLatest;

        $this->studentName = $name;
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
        $this->birth_place = $userprofile->birth_place;
        $this->native_place = $userprofile->native_place;
        $this->mother_tongue = $userprofile->mother_tongue;
        $this->caste = $userprofile->caste;
        $this->sub_caste = $userprofile->sub_caste;
        $this->notes = $userprofile->notes;
        $this->avatarPath = $userprofile->avatar;
        $this->avatarDisplay = $userprofile->AvatarPath;
        $this->registration_number = $user->registration_number ?? $userprofile->registration_number;
        $this->EMIS_number = $userprofile->EMIS_number;
        $this->joining_date = $userprofile->joining_date ? date('Y-m-d', strtotime($userprofile->joining_date)) : '';

        $this->standard = $studentAcademic->standardLink_id;
        $this->roll_number = $studentAcademic->roll_number;
        $this->id_card_number = $studentAcademic->id_card_number;
        $this->board_registration_number = $studentAcademic->board_registration_number;
        $this->mode_of_transport = $studentAcademic->mode_of_transport;
        $this->driver_name = $studentAcademic->transport_details['driver_name'] ?? '';
        $this->driver_contact_number = $studentAcademic->transport_details['driver_contact_number'] ?? '';

        $storedCustomFieldValues = CustomFieldHelper::getValues('student', $user->id);

        foreach ($this->customFields as $field) {
            $stored = $storedCustomFieldValues->get($field->id);

            if ($field->field_type === 'file') {
                $this->existingCustomFieldFiles[$field->id] = $stored?->file_path;

                continue;
            }

            $value = $stored?->value;
            $this->custom_fields[$field->id] = ($field->field_type === 'checkbox' && $value) ? explode(',', $value) : $value;
        }
    }

    public function isEdit(): bool
    {
        return $this->userId !== null;
    }

    /**
     * Upload the avatar to permanent storage the instant it's selected,
     * rather than waiting for save() to read the TemporaryUploadedFile at
     * the end of the form's lifetime. A raw uploaded-file object has to
     * survive every subsequent Livewire request (each field the user
     * touches after choosing a photo triggers one) intact, and in practice
     * that reference was getting dropped somewhere along the way — students
     * were ending up with their gender-default avatar instead of the photo
     * they picked, with no error shown. A plain string path in
     * $avatarPath has no such fragility, so save() can just read that.
     */
    public function updatedAvatar(): void
    {
        if (! $this->avatar instanceof TemporaryUploadedFile) {
            return;
        }

        // Format-check the raw upload directly rather than going through
        // rules(), whose 'avatar' entry checks $avatarPath (not yet set at
        // this point) rather than the file itself.
        $this->validate(['avatar' => 'mimes:jpg,jpeg,png'], [], ['avatar' => 'Avatar']);

        $folder = Auth::user()->school->slug.'/'.($this->isEdit() ? 'member' : 'student').'/avatar';
        $path = $this->uploadFile($folder, $this->avatar);

        if ($path === '') {
            \Illuminate\Support\Facades\Log::error('Student avatar upload failed to write to storage', [
                'school_id' => Auth::user()->school_id,
                'folder' => $folder,
                'is_edit' => $this->isEdit(),
                'student_name' => $this->studentName,
            ]);

            $this->addError('avatar', 'The photo could not be saved — please try selecting it again.');

            return;
        }

        $this->avatarPath = $path;
        $this->avatarDisplay = $this->getFilePath($this->avatarPath);
    }

    public function resetForm(): void
    {
        if ($this->isEdit()) {
            return;
        }

        $this->reset([
            'firstname',
            'lastname',
            'mobile_no',
            'email',
            'gender',
            'date_of_birth',
            'blood_group',
            'aadhar_number',
            'address',
            'state_id',
            'city_id',
            'pincode',
            'birth_place',
            'native_place',
            'mother_tongue',
            'caste',
            'sub_caste',
            'mode_of_transport',
            'driver_name',
            'driver_contact_number',
            'notes',
            'avatar',
            'registration_number',
            'EMIS_number',
            'standard',
            'roll_number',
            'id_card_number',
            'board_registration_number',
            'custom_fields',
        ]);

        $this->country_id = 7;
        $this->date_of_birth = date('Y-m-d', strtotime('-4 years'));
        $this->joining_date = date('Y-m-d');
        $this->resetErrorBag();
    }

    protected function rules(): array
    {
        $schoolId = Auth::user()->school_id;
        $nameRegex = 'regex:/^[A-Za-z\s]+$/';

        $aadharRule = Rule::unique('userprofiles', 'aadhar_number');
        if ($this->isEdit()) {
            $aadharRule = $aadharRule->ignore($this->userId, 'user_id');
        }

        $rules = [
            'firstname' => ['required', $nameRegex, 'max:15'],
            'lastname' => ['nullable', $nameRegex, 'max:15'],
            'date_of_birth' => ['required', 'date', function ($attribute, $value, $fail) {
                $start = date('Y-06-01', strtotime('-20 years'));
                $end = date('Y-06-01', strtotime('-3 years'));
                if ($value < $start || $value > $end) {
                    $fail('Date Of Birth should be between ' . date('d-m-Y', strtotime($start)) . ' and ' . date('d-m-Y', strtotime($end)) . '.');
                }
            }],
            'gender' => 'required',
            'blood_group' => 'required',
            'aadhar_number' => ['nullable', 'numeric', 'digits:12', $aadharRule],
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'pincode' => 'nullable|numeric|digits:6',
            'birth_place' => ['nullable', $nameRegex],
            'native_place' => ['nullable', $nameRegex],
            'mother_tongue' => ['required', $nameRegex],
            'caste' => 'required',
            'notes' => ['nullable', 'regex:/^[A-Za-z0-9_~\-!@#$%^&*.,:()\s]+$/'],
            'registration_number' => 'required|numeric',
            'EMIS_number' => 'nullable|numeric',
            'joining_date' => ['required', 'date', function ($attribute, $value, $fail) {
                $earliestYear = Carbon::now()->subYears(18)->format('Y');
                if ($value > date('Y-m-d') || date('Y', strtotime($value)) < $earliestYear) {
                    $fail('Select a valid joining date.');
                }
            }],
            'standard' => 'required',
            'roll_number' => 'required|numeric',
            'id_card_number' => 'nullable|numeric',
            'mode_of_transport' => 'nullable',
            'board_registration_number' => 'nullable|numeric',
        ];

        if ($this->isEdit()) {
            $rules['avatar'] = 'nullable|mimes:jpg,jpeg,png';
            $rules['driver_name'] = ['required', $nameRegex];
            $rules['driver_contact_number'] = 'required|numeric|digits:10';
        } else {
            // updatedAvatar() already validates the raw upload's mimes/format
            // as soon as it's selected and moves it to permanent storage in
            // $avatarPath — that's what actually needs to be present at
            // submit time, not the (possibly stale-by-then) file object.
            $rules['avatar'] = [function ($attribute, $value, $fail) {
                if (empty($this->avatarPath)) {
                    $fail('Avatar Is Required');
                }
            }];
            $rules['email'] = ['nullable', 'email', Rule::unique('users', 'email')];
            $rules['mobile_no'] = ['nullable', 'numeric', 'digits:10', Rule::unique('users', 'mobile_no')];
            $rules['driver_name'] = ['nullable', $nameRegex];
            $rules['driver_contact_number'] = ['nullable', 'numeric', 'digits:10', Rule::unique('users', 'mobile_no')];
        }

        $rules = array_merge($rules, $this->customFieldRulesWithExactMessages($schoolId));

        if (! in_array($this->mode_of_transport, ['auto', 'rickshaw', 'taxi'])) {
            unset($rules['driver_name'], $rules['driver_contact_number']);
        }

        if ($this->standard) {
            $standardLink = StandardLink::where('school_id', $schoolId)->find($this->standard);
            $standardName = $standardLink ? optional(Standard::where('school_id', $schoolId)->find($standardLink->standard_id))->name : null;

            if (in_array($standardName, ['10', '11', '12'])) {
                $rules['board_registration_number'] = 'required|numeric';
            }
        }

        return $rules;
    }

    /**
     * CustomFieldHelper::validationRules() falls back to a bare `regex:...`
     * rule for pattern-based custom fields, which only ever produces
     * Laravel's generic "The :attribute format is invalid." message with no
     * indication of what format was actually expected. Swap that portion
     * out for a closure that names the exact pattern the value must match.
     */
    protected function customFieldRulesWithExactMessages(int $schoolId): array
    {
        $rules = CustomFieldHelper::validationRules('student', $schoolId);
        $fields = CustomFieldHelper::getFieldsForEntity('student', $schoolId)->keyBy('id');

        foreach ($rules as $key => $rule) {
            $fieldId = (int) str_replace(['custom_fields.', '.regex'], '', $key);
            $field = $fields->get($fieldId);

            // On edit, a required file field that already has a
            // previously-uploaded value shouldn't force a fresh upload.
            if ($this->isEdit() && $field?->field_type === 'file' && is_string($rule) && str_starts_with($rule, 'required')) {
                $hasExisting = ! empty($this->existingCustomFieldFiles[$fieldId] ?? null);
                $hasNewUpload = ($this->custom_fields[$fieldId] ?? null) instanceof TemporaryUploadedFile;

                if ($hasExisting && ! $hasNewUpload) {
                    $rules[$key] = $rule = str_replace('required', 'nullable', $rule);
                }
            }

            if (! is_string($rule) || ! str_contains($rule, 'regex:')) {
                continue;
            }
            $config = $field?->entityConfigs->first();
            $pattern = $config ? CustomFieldHelper::resolveAdditionalRule($field, $config) : null;
            $pattern = $pattern ? str_replace('regex:', '', $pattern) : null;

            $parts = array_filter(explode('|', $rule), fn($part) => ! str_starts_with($part, 'regex:'));
            $example = $pattern ? $this->exampleForPattern($pattern) : null;

            $parts[] = function ($attribute, $value, $fail) use ($field, $pattern, $example) {
                if ($pattern && $value !== null && $value !== '' && ! preg_match($pattern, $value)) {
                    $fail($example
                        ? "{$field->label} format is invalid (e.g. {$example})."
                        : "{$field->label} format is invalid.");
                }
            };

            $rules[$key] = $parts;
        }

        return $rules;
    }

    /**
     * Find a sample value that satisfies an admin-defined custom-field
     * regex, so validation failures can show users a concrete example
     * instead of the raw pattern. Tries a pool of common formats
     * (numeric, alphabetic, alphanumeric, decimal, url, email) and
     * returns the first one that matches; null if none fit.
     */
    protected function exampleForPattern(string $pattern): ?string
    {
        $candidates = [];

        for ($len = 1; $len <= 20; $len++) {
            $candidates[] = substr(str_repeat('123456789', 3), 0, $len);
            $candidates[] = str_pad('9', $len, '0');
        }

        $candidates = array_merge($candidates, [
            'JohnDoe',
            'abcdef',
            'ABCDEF',
            'John Doe',
            'ABC123',
            'abc123XYZ',
            '123.45',
            '0.5',
            'https://example.com',
            'name@example.com',
        ]);

        foreach ($candidates as $candidate) {
            if (@preg_match($pattern, $candidate) === 1) {
                return $candidate;
            }
        }

        return null;
    }

    protected function messages(): array
    {
        $messages = [
            'firstname.required' => 'First Name Is Required',
            'firstname.regex' => 'Enter A Valid First Name',
            'firstname.max' => 'First Name Should Be Atmost 15 Characters',
            'lastname.regex' => 'Enter A Valid Last Name',
            'lastname.max' => 'Last Name Should Be Atmost 15 Characters',
            'email.email' => 'Enter A Valid Email ID',
            'email.unique' => 'Email ID Already In Use. Enter Different Email ID',
            'mobile_no.digits' => 'Mobile Number Should Be 10 Digits',
            'mobile_no.unique' => 'Mobile Number Already In Use. Enter Different Mobile Number',
            'date_of_birth.required' => 'Date Of Birth Is Required',
            'gender.required' => 'Gender Is Required',
            'blood_group.required' => 'Blood Group Is Required',
            'aadhar_number.digits' => 'Aadhaar Number Should Be Of 12 Digits',
            'aadhar_number.unique' => 'Aadhaar Number Already Exists. Enter Different Aadhaar Number',
            'city_id.required' => 'City Is Required',
            'state_id.required' => 'State Is Required',
            'country_id.required' => 'Country Is Required',
            'pincode.digits' => 'Pincode Should Be 6 Digits',
            'birth_place.regex' => 'Enter Valid Birth Place',
            'native_place.regex' => 'Enter Valid Native Place',
            'mother_tongue.required' => 'Mother Tongue Is Required',
            'mother_tongue.regex' => 'Enter Valid Mother Tongue',
            'caste.required' => 'Caste Is Required',
            'avatar.required' => 'Avatar Is Required',
            'avatar.mimes' => 'Choose jpg,jpeg,png File',
            'notes.regex' => 'Enter Valid Notes',
            'registration_number.required' => 'Admission Number Is Required',
            'registration_number.numeric' => 'Admission Number Should Be Numeric',
            'EMIS_number.numeric' => 'EMIS Number Should Be Numeric',
            'joining_date.required' => 'Joining Date Is Required',
            'standard.required' => 'Class Is Required',
            'roll_number.required' => 'Roll Number Is Required',
            'roll_number.numeric' => 'Roll Number Should Be Numeric',
            'id_card_number.numeric' => 'ID Card Number Should Be Numeric',
            'board_registration_number.required' => 'Board Registration Number Is Required (Class X, XI, XII)',
            'board_registration_number.numeric' => 'Board Registration Number Should Be Numeric',
            'driver_name.required' => 'Driver Name Is Required',
            'driver_name.regex' => 'Enter Valid Driver Name',
            'driver_contact_number.required' => 'Driver Contact Number Is Required',
            'driver_contact_number.digits' => 'Driver Contact Number Should Be 10 Digits',
            'driver_contact_number.unique' => 'Contact Number Already In Use. Enter Different Contact Number',
        ];

        return $messages;
    }

    protected function validationAttributes(): array
    {
        return CustomFieldHelper::validationAttributes('student', Auth::user()->school_id);
    }

    public function save()
    {
        $this->validate();

        $schoolId = Auth::user()->school_id;
        $academicYear = SiteHelper::getAcademicYear($schoolId);

        // updatedAvatar() already moved the file to permanent storage the
        // moment it was selected and recorded its path here.
        $path = $this->avatarPath ?? '';

        if ($this->isEdit()) {
            $userprofile = $this->UpdateUser($this, $schoolId, $academicYear->id, $this->userId, $path);

            $this->persistCustomFields($this->userId, $schoolId);

            $message = trans('messages.update_success_msg', ['module' => 'Student']);

            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $this->getRequestIP(), 'details' => $_SERVER['HTTP_USER_AGENT'] ?? ''],
                LOGNAME_EDIT_STUDENT,
                $message
            );

            session()->put('successmessage', $message);

            $this->redirect(url('/admin/student/edit/' . $this->studentName), navigate: false);

            return;
        }

        $user = $this->CreateUser($this, $schoolId, $academicYear->id, $path, 6);

        $this->persistCustomFields($user->id, $schoolId);

        $message = trans('messages.add_success_msg', ['module' => 'Student']);

        $this->doActivityLog(
            $user,
            Auth::user(),
            ['ip' => $this->getRequestIP(), 'details' => $_SERVER['HTTP_USER_AGENT'] ?? ''],
            LOGNAME_ADD_STUDENT,
            $message
        );

        session()->put('successmessage', $message);

        $this->redirect(url('/admin/students'), navigate: false);
    }

    protected function persistCustomFields(int $userId, int $schoolId): void
    {
        foreach (CustomFieldHelper::getFieldsForEntity('student', $schoolId) as $field) {
            $value = $this->custom_fields[$field->id] ?? null;

            if ($field->field_type === 'file') {
                if ($value instanceof TemporaryUploadedFile) {
                    $path = $this->uploadFile($schoolId . '/custom-fields', $value);
                    CustomFieldHelper::setValue($field->id, 'student', $userId, $schoolId, null, $path);
                }

                continue;
            }

            if ($value === null || $value === '') {
                continue;
            }

            $value = is_array($value) ? implode(',', $value) : $value;

            CustomFieldHelper::setValue($field->id, 'student', $userId, $schoolId, $value);
        }
    }

    public function render()
    {
        return view('livewire.admin.student.student-form');
    }
}
