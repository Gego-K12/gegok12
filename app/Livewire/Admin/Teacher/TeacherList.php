<?php

// SPDX-License-Identifier: MIT
// (c) 2026 GegoSoft Technologies and GegoK12 Contributors

namespace App\Livewire\Admin\Teacher;

use App\Events\SendMessageTeacherEvent;
use App\Helpers\SiteHelper;
use App\Models\Users\TeacherUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Teaching staff list -- ports resources/assets/js/components/teacher/List.vue,
 * Filter.vue, NameCell.vue and export/Teacher.vue into a single Livewire component.
 */
class TeacherList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $view = 'current';

    public string $alphabet = '';

    public string $firstname = '';

    public string $lastname = '';

    public string $mobileNo = '';

    public string $email = '';

    public string $qualification = '';

    public string $designation = '';

    public string $gender = '';

    public string $bloodGroup = '';

    public string $maritalStatus = '';

    public string $dateOfBirthMonth = '';

    public string $jobType = '';

    public bool $showFilters = false;

    /**
     * Every filter property above resets pagination/selection when changed; listed
     * once here instead of one updating*() hook per property (there are 13 of them).
     */
    protected array $filterProperties = [
        'view', 'alphabet', 'firstname', 'lastname', 'mobileNo', 'email',
        'qualification', 'designation', 'gender', 'bloodGroup', 'maritalStatus',
        'dateOfBirthMonth', 'jobType',
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
        $academicYear = SiteHelper::getAcademicYear($schoolId);

        $query = TeacherUser::where('school_id', $schoolId)
            ->ByRole(5)
            ->with([
                'standardLink' => fn ($q) => $q->where('academic_year_id', $academicYear->id),
                'teacherlink' => fn ($q) => $q->where('academic_year_id', $academicYear->id),
                'teacherlink.standardLink',
                'teacherlink.subject',
                'lastLogin',
                'userprofile',
                'latestTeacherProfile',
            ])
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

        if ($this->qualification) {
            $query->ByQualification($this->qualification);
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
            session()->flash('error', 'Select Teachers');

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
        Session::put('teacher_headings', array_values($this->exportColumns));
        $this->closeExportModal();

        return redirect()->to(url('/admin/teacher/export').'?'.$this->filterQueryString());
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
            'qualification' => $this->qualification,
            'designation' => $this->designation,
            'marital_status' => $this->maritalStatus,
            'job_type' => $this->jobType,
        ]));
    }

    public function render()
    {
        $teachers = $this->baseQuery()->paginate(20);

        $totalStaffCount = TeacherUser::where('school_id', Auth::user()->school_id)->ByRole(5)->count();

        return view('livewire.admin.teacher.teacher-list', [
            'teachers' => $teachers,
            'totalStaffCount' => $totalStaffCount,
            'qualifications' => SiteHelper::getAdditionalCertificates(),
            'designations' => SiteHelper::getTeachingDesignations(),
            'bloodGroups' => SiteHelper::getBloodGroups(),
            'maritalOptions' => SiteHelper::getMaritalList(),
            'exportableColumns' => $this->exportableColumns,
        ]);
    }
}
