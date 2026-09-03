<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Livewire\Admin\Student\Profile;

use App\Helpers\SiteHelper;
use App\Http\Resources\LeaveHistory as LeaveHistoryResource;
use App\Models\TeacherLeaveApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Leave History tab on the Student Profile page.
 *
 * The original Vue component hit the teacher-only
 * `/admin/teacher/show/leave/{name}` endpoint (TeacherShowController::
 * showLeaveHistory), which resolves the name against TeacherUser -- so for a
 * student's name it always resolved to null and silently returned an empty
 * page. TeacherLeaveApplication is keyed by a generic user_id (student leave
 * applications are recorded there too, approved by teachers/reception -- see
 * Teacher\StudentLeaveController), so this queries by the student's own User
 * record instead of TeacherUser to actually surface the student's leave
 * history.
 */
class LeaveHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $name;

    public function mount(string $name)
    {
        $this->name = $name;
    }

    public function render()
    {
        $user = User::where('name', $this->name)->first();
        $schoolId = Auth::user()->school_id;
        $academicYear = SiteHelper::getAcademicYear($schoolId);

        $leaves = TeacherLeaveApplication::where([
            ['user_id', $user->id],
            ['school_id', $schoolId],
            ['academic_year_id', $academicYear->id],
        ])->paginate(5)
            ->through(fn ($leave) => (new LeaveHistoryResource($leave))->toArray(request()));

        return view('livewire.admin.student.profile.leave-history', ['leaves' => $leaves]);
    }
}
