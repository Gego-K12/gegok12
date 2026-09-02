<?php

// SPDX-License-Identifier: MIT
// (c) 2025 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Parent;

use App\Helpers\CustomFieldHelper;
use App\Helpers\SiteHelper;
use App\Models\StudentAcademic;
use App\Models\StudentParentLink;
use App\Models\User;
use App\Models\Userprofile;
use App\Models\Users\ParentUser;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\RegisterUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ParentForm extends Component
{
    use Common;
    use LogActivity;
    use RegisterUser;

    public ?string $parentName = null;

    public ?int $userId = null;

    public ?string $name = null;

    public string $ref_name = '';

    public string $mode = 'add';

    /** @var string Read by RegisterUser::CreateParent() as $data->parent ('add'|'select'). */
    public $parent = 'add';

    /** @var int|null Read by RegisterUser::CreateParent() as $data->select_id. */
    public $select_id = null;

    public $standardLinkId = '';

    public array $parentOptions = [];

    public array $selectedParentIds = [];

    public $firstname = '';

    public $lastname = '';

    public $email = '';

    public $mobile_no = '';

    public $alternate_no = '';

    public array $qualifications = [''];

    public $qualification_id = null;

    public $profession = '';

    public $sub_occupation = '';

    public $designation = '';

    public $organization_name = '';

    public $official_address = '';

    public $relation = '';

    public $annual_income = '';

    public $siblings = '';

    public $siblings_count = '';

    public array $siblingRows = [
        ['sibling_relation' => '', 'sibling_name' => '', 'sibling_date_of_birth' => '', 'sibling_standard' => ''],
    ];

    public $sibling_relation = [];

    public $sibling_name = [];

    public $sibling_date_of_birth = [];

    public $sibling_standard = [];

    public array $custom_fields = [];

    public $customFields;

    public array $qualificationlist = [];

    public array $standardLinklist = [];

    public array $professions = [
        ['num' => 'business', 'name' => 'Business'],
        ['num' => 'central_government_employee', 'name' => 'Central Government Employee'],
        ['num' => 'private', 'name' => 'Private'],
        ['num' => 'home_maker', 'name' => 'Home Maker'],
        ['num' => 'state_government_employee', 'name' => 'State Government Employee'],
        ['num' => 'others', 'name' => 'Others'],
    ];

    public array $occupationlist = ['business', 'central_government_employee', 'private', 'state_government_employee', 'others'];

    public array $siblinglist = [
        ['id' => 'brother', 'name' => 'Brother'],
        ['id' => 'sister', 'name' => 'Sister'],
    ];

    public function mount($name = null, $ref_name = null)
    {
        $schoolId = Auth::user()->school_id;

        $this->ref_name = $ref_name ?? (request('ref_name') ?: '');
        $this->qualificationlist = json_decode(json_encode(SiteHelper::getQualifications()), true) ?? [];
        $this->standardLinklist = json_decode(json_encode(SiteHelper::getStandardLinkList($schoolId)), true) ?? [];
        $this->customFields = CustomFieldHelper::getFieldsForEntity('parent', $schoolId);

        if (! $name) {
            return;
        }

        $user = ParentUser::where('name', $name)->firstOrFail();

        abort_unless(Gate::allows('member', $user), 403);

        $userprofile = Userprofile::where('user_id', $user->id)->first();
        $parentprofile = $user->getParentDetails();

        $this->parentName = $name;
        $this->userId = $user->id;

        $this->firstname = $userprofile->firstname;
        $this->lastname = $userprofile->lastname;
        $this->alternate_no = $userprofile->alternate_no;
        $this->profession = $parentprofile['profession'] ?? '';
        $this->sub_occupation = $parentprofile['sub_occupation'] ?? '';
        $this->designation = $parentprofile['designation'] ?? '';
        $this->organization_name = $parentprofile['organization_name'] ?? '';
        $this->official_address = $parentprofile['official_address'] ?? '';
        $this->annual_income = $parentprofile['annual_income'] ?? '';
        $this->relation = $parentprofile['relation'] ?? '';

        if (! empty($parentprofile['qualification_id'])) {
            $this->qualifications = array_map(
                fn ($row) => $row['qualification_id'],
                $parentprofile['qualification_id']
            );
        }

        $student = $this->ref_name !== ''
            ? User::where('name', $this->ref_name)->first()
            : optional(StudentParentLink::where('parent_id', $user->id)->orderByDesc('id')->first(), fn ($link) => User::where('id', $link->student_id)->first());

        if ($student) {
            $academic = StudentAcademic::where('user_id', $student->id)->orderByDesc('id')->first();

            if ($academic) {
                $this->siblings = $academic->siblings ?? '';
                $this->siblings_count = $academic->siblings_count ?? '';

                if ($this->siblings === 'yes' && ! empty($academic->sibling_details)) {
                    $this->siblingRows = array_map(function ($sibling) {
                        $dob = is_array($sibling['sibling_date_of_birth']) ? ($sibling['sibling_date_of_birth'][0] ?? '') : $sibling['sibling_date_of_birth'];

                        return [
                            'sibling_relation' => $sibling['sibling_relation'],
                            'sibling_name' => $sibling['sibling_name'],
                            'sibling_date_of_birth' => $dob ? date('Y-m-d', strtotime($dob)) : '',
                            'sibling_standard' => $sibling['sibling_standard'],
                        ];
                    }, $academic->sibling_details);
                }
            }
        }

        $storedCustomFieldValues = CustomFieldHelper::getValues('parent', $user->id);

        foreach ($this->customFields as $field) {
            $stored = $storedCustomFieldValues->get($field->id);
            $value = $stored?->value;
            $this->custom_fields[$field->id] = ($field->field_type === 'checkbox' && $value) ? explode(',', $value) : $value;
        }
    }

    public function isEdit(): bool
    {
        return $this->userId !== null;
    }

    public function updatedStandardLinkId(): void
    {
        $this->selectedParentIds = [];
        $this->parentOptions = [];

        if ($this->standardLinkId === '') {
            return;
        }

        $users = ParentUser::where('school_id', Auth::user()->school_id)
            ->ByRole(7)
            ->ByStandardLinkParentList($this->standardLinkId)
            ->with('userprofile')
            ->get();

        $this->parentOptions = $users->map(fn ($user) => [
            'id' => $user->id,
            'fullname' => trim($user->userprofile->firstname.' '.$user->userprofile->lastname),
            'mobile_no' => $user->mobile_no,
        ])->all();
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

    public function addSiblingRow(): void
    {
        $this->siblingRows[] = ['sibling_relation' => '', 'sibling_name' => '', 'sibling_date_of_birth' => '', 'sibling_standard' => ''];
    }

    public function removeSiblingRow(int $index): void
    {
        unset($this->siblingRows[$index]);
        $this->siblingRows = array_values($this->siblingRows);
    }

    public function hasOccupationDetails(): bool
    {
        return $this->profession !== '' && $this->profession !== 'home_maker';
    }

    protected function rules(): array
    {
        $schoolId = Auth::user()->school_id;
        $nameRegex = $this->isEdit() ? 'regex:/^[\pL\s]+$/u' : 'regex:/^[A-Za-z\s]+$/';
        $punctuationRegex = 'regex:/^[A-Za-z_~\-!@#$%^&*.,:()\s]+$/';

        if (! $this->isEdit() && $this->mode === 'select') {
            return ['selectedParentIds' => 'required|array|min:1'];
        }

        $rules = [
            'firstname' => ['required', $nameRegex, 'max:35'],
            'lastname' => ['nullable', $nameRegex, 'max:15'],
            'alternate_no' => 'nullable|numeric|digits:10',
            'profession' => 'required',
            'relation' => 'required',
        ];

        if (! $this->isEdit()) {
            $rules['firstname'] = ['required', $nameRegex, 'max:35'];
            $rules['email'] = ['nullable', 'email', Rule::unique('users', 'email')];
            $rules['mobile_no'] = [
                'required', 'numeric', 'digits:10',
                Rule::unique('users', 'mobile_no')->where(fn ($query) => $query->where('usergroup_id', 7)),
            ];
        }

        if ($this->hasOccupationDetails()) {
            $rules['sub_occupation'] = ['nullable', $punctuationRegex, 'max:15'];
            $rules['designation'] = ['nullable', $punctuationRegex];
            $rules['organization_name'] = ['nullable', $punctuationRegex];
            $rules['annual_income'] = ['required', 'numeric', 'digits_between:4,9'];
        }

        $rules['siblings'] = 'required';

        if ($this->siblings === 'yes') {
            $rules['siblings_count'] = 'required|numeric';

            foreach (array_keys($this->siblingRows) as $index) {
                $rules["siblingRows.{$index}.sibling_relation"] = 'required';
                $rules["siblingRows.{$index}.sibling_name"] = ['required', 'regex:/^[A-Za-z\s]+$/'];
                $rules["siblingRows.{$index}.sibling_date_of_birth"] = [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        if ($value > date('Y-m-d') || $value < '2000-01-01') {
                            $fail('Enter a valid sibling date of birth.');
                        }
                    },
                ];
                $rules["siblingRows.{$index}.sibling_standard"] = 'nullable';
            }
        }

        if (! $this->isEdit()) {
            $rules = array_merge($rules, CustomFieldHelper::validationRules('parent', $schoolId));
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'firstname.required' => 'First Name Is Required',
            'firstname.regex' => 'Enter A Valid First Name',
            'firstname.max' => 'First Name Should Be Atmost 35 Characters',
            'lastname.regex' => 'Enter A Valid Last Name',
            'lastname.max' => 'Last Name Should Be Atmost 15 Characters',
            'email.email' => 'Enter A Valid Email ID',
            'email.unique' => 'Email ID Already In Use. Enter Different Email ID',
            'mobile_no.required' => 'Mobile Number Is Required',
            'mobile_no.digits' => 'Mobile Number Should Be 10 Digits',
            'mobile_no.unique' => 'Mobile Number Already In Use. Enter Different Mobile Number',
            'alternate_no.numeric' => 'Alternate Number Should Be Numeric',
            'alternate_no.digits' => 'Alternate Number Should Be 10 Digits',
            'profession.required' => 'Occupation Is Required',
            'relation.required' => 'Choose A Relation',
            'sub_occupation.regex' => 'Enter Valid Sub Category',
            'sub_occupation.max' => 'Sub Category Should Be Atmost 15 Characters',
            'designation.regex' => 'Enter Valid Designation',
            'organization_name.regex' => 'Enter Valid Organization Name',
            'annual_income.required' => 'Annual Income Is Required',
            'annual_income.numeric' => 'Annual Income Should Be Numeric',
            'annual_income.digits_between' => 'Annual Income Should Be Between 4 And 9 Digits',
            'siblings.required' => 'Siblings Is Required',
            'siblings_count.required' => 'Siblings Count Is Required',
            'siblings_count.numeric' => 'Siblings Count Should Be Numeric',
            'selectedParentIds.required' => 'Select A Parent',
        ];
    }

    protected function validationAttributes(): array
    {
        if ($this->isEdit()) {
            return [];
        }

        return CustomFieldHelper::validationAttributes('parent', Auth::user()->school_id);
    }

    public function save()
    {
        $this->validate();

        $schoolId = Auth::user()->school_id;

        // users.email is uniquely indexed; an empty string (rather than
        // null) is a distinct, collidable value there, so a second parent
        // created with no email would fail with a duplicate-key error.
        if (! $this->isEdit() && $this->email === '') {
            $this->email = null;
        }

        $student = $this->ref_name !== '' ? User::where('name', $this->ref_name)->first() : null;
        $studentId = $student->id ?? null;

        $qualifications = array_values(array_filter($this->qualifications, fn ($q) => $q !== null && $q !== ''));
        $this->qualification_id = $qualifications !== [] ? $qualifications : null;

        if ($this->siblings === 'yes') {
            $this->sibling_relation = array_column($this->siblingRows, 'sibling_relation');
            $this->sibling_name = array_column($this->siblingRows, 'sibling_name');
            $this->sibling_date_of_birth = array_column($this->siblingRows, 'sibling_date_of_birth');
            $this->sibling_standard = array_column($this->siblingRows, 'sibling_standard');
        }

        if ($this->isEdit()) {
            $userprofile = $this->UpdateParent($studentId, $this, $schoolId, $this->userId);

            $this->persistCustomFields($this->userId, $schoolId);

            $message = trans('messages.update_success_msg', ['module' => 'Parent']);

            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $this->getRequestIP(), 'details' => $_SERVER['HTTP_USER_AGENT'] ?? ''],
                LOGNAME_EDIT_PARENT,
                $message
            );

            session()->put('successmessage', $message);

            $this->redirect(url('/admin/parent/edit/'.$this->parentName.($this->ref_name !== '' ? '?ref_name='.$this->ref_name : '')), navigate: false);

            return;
        }

        $message = trans('messages.add_success_msg', ['module' => 'Parent']);

        if ($this->mode === 'select') {
            foreach ($this->selectedParentIds as $selectedId) {
                $this->select_id = $selectedId;
                $this->parent = 'select';

                $user = $this->CreateParent($studentId, $this, $schoolId, 7);

                $this->doActivityLog(
                    $user,
                    Auth::user(),
                    ['ip' => $this->getRequestIP(), 'details' => $_SERVER['HTTP_USER_AGENT'] ?? ''],
                    LOGNAME_ADD_PARENT,
                    $message
                );
            }
        } else {
            $this->parent = 'add';

            $user = $this->CreateParent($studentId, $this, $schoolId, 7);

            $this->persistCustomFields($user->id, $schoolId);

            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $this->getRequestIP(), 'details' => $_SERVER['HTTP_USER_AGENT'] ?? ''],
                LOGNAME_ADD_PARENT,
                $message
            );
        }

        session()->put('successmessage', $message);

        $this->redirect(url('/admin/student/show/'.$this->ref_name), navigate: false);
    }

    protected function persistCustomFields(int $userId, int $schoolId): void
    {
        foreach (CustomFieldHelper::getFieldsForEntity('parent', $schoolId) as $field) {
            $value = $this->custom_fields[$field->id] ?? null;

            if ($value === null || $value === '') {
                continue;
            }

            $value = is_array($value) ? implode(',', $value) : $value;

            CustomFieldHelper::setValue($field->id, 'parent', $userId, $schoolId, $value);
        }
    }

    public function render()
    {
        return view('livewire.admin.parent.parent-form');
    }
}
