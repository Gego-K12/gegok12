<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Staff;

use App\Events\SendMessageTeacherEvent;
use App\Helpers\SiteHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Non-teaching staff list -- ports resources/assets/js/components/staff/List.vue,
 * Filter.vue and export/Staff.vue into a single Livewire component. Mirrors
 * App\Livewire\Admin\Teacher\TeacherList, minus the teacher-only Primary
 * Role / Subject Teacher to columns and the Additional Certificates filter
 * (which is commented out, unused, in the original Filter.vue).
 */
class StaffList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $view = 'current';

    public string $alphabet = '';

    public string $firstname = '';

    public string $lastname = '';

    public string $mobileNo = '';

    public string $email = '';

    public string $designation = '';

    public string $gender = '';

    public string $bloodGroup = '';

    public string $maritalStatus = '';

    public string $dateOfBirthMonth = '';

    public string $jobType = '';

    public bool $showFilters = false;

    /**
     * Every filter property above resets pagination/selection when changed; listed
     * once here instead of one updating*() hook per property.
     */
    protected array $filterProperties = [
        'view', 'alphabet', 'firstname', 'lastname', 'mobileNo', 'email',
        'designation', 'gender', 'bloodGroup', 'maritalStatus', 'dateOfBirthMonth', 'jobType',
    ];

    public array $selected = [];

    public bool $showMessageModal = false;

    public string $subject = '';

    public string $message = '';

    public bool $sendLater = false;

    public string $executedAt = '';

    public bool $showExportModal = false;

    public array $exportColumns = [];

    protected array $exportableColumns = [
        'employee_id' => 'Employee Id',
        'designation' => 'Designation',
        'name' => 'Name',
        'email' => 'Email',
        'mobile_no' => 'Mobile Number',
        'gender' => 'Gender',
        'Joining_date' => 'Joining Date',
        'adhaar' => 'Aadhar Number',
        'blood_group' => 'Blood Group',
        'date_of_birth' => 'Date of Birth',
        'address' => 'Address',
        'city' => 'City',
        'state' => 'State',
        'country' => 'Country',
        'pincode' => 'Pincode',
    ];

    /**
     * Non-teaching usergroup ids -- matches StaffController@index/@find
     * (App\Traits\MemberProcess::StaffFilter is the shared query, but this
     * Livewire component builds its own query directly, the same way
     * TeacherList does, rather than going through that trait method).
     */
    protected function usergroupIds(): array
    {
        $groups = [
            User::LIBRARIAN_USERGROUP_ID,
            User::RECEPTIONIST_USERGROUP_ID,
            User::ACCOUNTANT_USERGROUP_ID,
            User::NON_TEACHING_USERGROUP_ID,
        ];

        if (config('ginventory.enabled', false)) {
            $groups[] = User::STOCK_KEEPER_USERGROUP_ID;
        }

        return $groups;
    }

    public function updated(string $property): void
    {
        if (in_array($property, $this->filterProperties, true)) {
            $this->resetPage();
            $this->selected = [];
        }
    }

    public function resetFilters(): void
    {
        $this->reset($this->filterProperties);
        $this->resetPage();
        $this->selected = [];
    }

    protected function baseQuery()
    {
        $schoolId = Auth::user()->school_id;

        $query = User::where('school_id', $schoolId)
            ->whereIn('usergroup_id', $this->usergroupIds())
            ->with(['lastLogin', 'userprofile', 'latestTeacherProfile'])
            ->whereHas('userprofile', function ($q) {
                if ($this->view === 'exit') {
                    $q->where('status', 'exit');
                } else {
                    $q->where('status', 'active')->orWhere('status', 'inactive');
                }
            });

        if ($this->alphabet) {
            $query->ByFirstName($this->alphabet);
        }

        if ($this->firstname) {
            $query->ByFirstName($this->firstname);
        }

        if ($this->lastname) {
            $query->ByLastName($this->lastname);
        }

        if ($this->gender) {
            $query->ByGender($this->gender);
        }

        if ($this->dateOfBirthMonth) {
            $query->ByDateOfBirth($this->dateOfBirthMonth);
        }

        if ($this->mobileNo) {
            $query->ByMobileNo($this->mobileNo);
        }

        if ($this->email) {
            $query->ByEmailId($this->email);
        }

        if ($this->bloodGroup) {
            $query->ByBloodGroup($this->bloodGroup);
        }

        if ($this->designation) {
            $query->ByDesignation($this->designation);
        }

        if ($this->maritalStatus) {
            $query->ByMaritalStatus($this->maritalStatus);
        }

        if ($this->jobType) {
            $query->ByJobType($this->jobType);
        }

        return $query;
    }

    public function selectAllMatching(): void
    {
        $this->selected = $this->baseQuery()->pluck('id')->map(fn ($id) => (string) $id)->all();
    }

    public function clearSelection(): void
    {
        $this->selected = [];
    }

    public function openMessageModal(): void
    {
        if (empty($this->selected)) {
            session()->flash('error', 'Select Staff');

            return;
        }

        $this->showMessageModal = true;
    }

    public function closeMessageModal(): void
    {
        $this->reset('showMessageModal', 'subject', 'message', 'sendLater', 'executedAt');
    }

    public function sendMessage(): void
    {
        $this->validate([
            'subject' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:1000'],
            'executedAt' => [$this->sendLater ? 'required' : 'nullable'],
        ], [], ['subject' => 'Subject', 'message' => 'Message', 'executedAt' => 'Date and Time']);

        $data = (object) [
            'selected' => $this->selected,
            'subject' => $this->subject,
            'message' => $this->message,
            'send_later' => $this->sendLater,
            'executed_at' => $this->executedAt,
        ];

        // No dedicated staff message event exists; SendMessageController@storeStaff
        // dispatches this same event class for non-teaching staff too.
        event(new SendMessageTeacherEvent($data, Auth::user()->school_id, Auth::user()->email, Auth::user()));

        session()->flash('success', trans('messages.message_success_msg'));

        $this->closeMessageModal();
        $this->selected = [];
    }

    public function openExportModal(): void
    {
        $this->exportColumns = [];
        $this->showExportModal = true;
    }

    public function closeExportModal(): void
    {
        $this->reset('showExportModal', 'exportColumns');
    }

    public function toggleAllExportColumns(bool $checkAll): void
    {
        $this->exportColumns = $checkAll ? array_keys($this->exportableColumns) : [];
    }

    public function submitExport()
    {
        Session::put('staff_headings', array_values($this->exportColumns));
        $this->closeExportModal();

        return redirect()->to(url('/admin/staff/export').'?'.$this->filterQueryString());
    }

    protected function filterQueryString(): string
    {
        return http_build_query(array_filter([
            'view' => $this->view === 'exit' ? 'exit' : null,
            'alphabet' => $this->alphabet,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'date_of_birth' => $this->dateOfBirthMonth,
            'mobile_no' => $this->mobileNo,
            'email' => $this->email,
            'blood_group' => $this->bloodGroup,
            'designation' => $this->designation,
            'marital_status' => $this->maritalStatus,
            'job_type' => $this->jobType,
        ]));
    }

    public function render()
    {
        $staff = $this->baseQuery()->paginate(20);

        $totalStaffCount = User::where('school_id', Auth::user()->school_id)
            ->whereIn('usergroup_id', $this->usergroupIds())
            ->count();

        return view('livewire.admin.staff.staff-list', [
            'staff' => $staff,
            'totalStaffCount' => $totalStaffCount,
            'designations' => SiteHelper::getNonTeachingDesignations(),
            'bloodGroups' => SiteHelper::getBloodGroups(),
            'maritalOptions' => SiteHelper::getMaritalList(),
            'exportableColumns' => $this->exportableColumns,
        ]);
    }
}
