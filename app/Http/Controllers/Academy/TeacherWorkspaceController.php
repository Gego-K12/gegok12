<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Services\AcademyTeacherWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherWorkspaceController extends Controller
{
    public function testAccounts(Request $request)
    {
        app(\App\Services\AcademyStudents::class)->admin($request->user());
        $accounts = DB::table('users')->where('school_id', $request->user()->school_id)->whereIn('email', config('academy_backend.demo_emails'))->whereNull('deleted_at')->get(['usergroup_id'])->keyBy('usergroup_id');
        return view('academy.test-accounts', compact('accounts'));
    }
    public function leaves(Request $request, AcademyTeacherWorkspace $service)
    {
        $query = $service->leaves($request->user());
        if (in_array($request->query('status'), ['pending', 'approved', 'rejected', 'cancelled'], true)) {
            if ($request->query('status') === 'rejected') { $query->where('l.academy_decision', 'rejected'); }
            else { $query->where('l.status', $request->query('status')); if ($request->query('status') === 'cancelled') { $query->whereNull('l.academy_decision'); } }
        }
        $leaves = $query->orderByDesc('l.id')->paginate(20, ['l.*', 'p.firstname', 'p.lastname'])->withQueryString();
        return view('academy.leaves', compact('leaves') + ['role' => (int) $request->user()->usergroup_id, 'layout' => (int) $request->user()->usergroup_id === 3 ? 'layouts.admin.layout' : 'layouts.teacher.layout']);
    }
    public function requestLeave(Request $request, AcademyTeacherWorkspace $service) { $service->requestLeave($request->user(), $request->all()); return back()->with('successmessage', 'Leave request sent to the administrator.'); }
    public function review(Request $request, int $leave, AcademyTeacherWorkspace $service) { $service->review($request->user(), $leave, $request->all()); return back()->with('successmessage', 'Decision saved and visible in the teacher account.'); }
    public function withdraw(Request $request, int $leave, AcademyTeacherWorkspace $service) { $service->withdraw($request->user(), $leave); return back()->with('successmessage', 'Request withdrawn.'); }
    public function attendance(Request $request, AcademyTeacherWorkspace $service)
    {
        $actor = $request->user(); $q = $service->attendance($actor, $request->integer('teacher_id') ?: null);
        $counts = (clone $q)->selectRaw('SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) as present_count, SUM(CASE WHEN a.status = 0 THEN 1 ELSE 0 END) as absent_count')->first();
        $records = $q->orderByDesc('a.date')->orderBy('a.session')->paginate(30, ['a.*', 'p.firstname', 'p.lastname'])->withQueryString();
        $teachers = (int) $actor->usergroup_id === 3 ? DB::table('users')->where('school_id', $actor->school_id)->where('usergroup_id', 5)->whereNull('deleted_at')->when(config('academy_backend.test_only'), fn ($q) => $q->where('email', config('academy_backend.demo_emails.5')))->orderBy('email')->get(['id', 'email']) : collect();
        return view('academy.teacher-attendance', compact('records', 'teachers', 'counts') + ['role' => (int) $actor->usergroup_id, 'layout' => (int) $actor->usergroup_id === 3 ? 'layouts.admin.layout' : 'layouts.teacher.layout']);
    }
    public function markAttendance(Request $request, AcademyTeacherWorkspace $service) { $service->markAttendance($request->user(), $request->all()); return back()->with('successmessage', 'Teacher attendance saved.'); }
}
