<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Services\AcademyLessons;
use App\Services\LearningAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupLessonController extends Controller
{
    public function show(Request $request, int $group, AcademyLessons $lessons, ?int $lesson = null)
    {
        $actor = $request->user(); $record = $lessons->group($actor, $group);
        $roster = $lessons->roster($actor, $group)->get();
        $summaries = $lessons->summary((int) $actor->school_id, $roster->pluck('id')->all());
        $teacher = DB::table('userprofiles')->where('user_id', $record->class_teacher_id)->where('school_id', $actor->school_id)->whereNull('deleted_at')->first(['firstname', 'lastname']);
        $history = DB::table('academy_group_lessons')->where('school_id', $actor->school_id)->where('group_id', $group)->orderByDesc('lesson_date')->orderByDesc('start_time')->paginate(15, ['*'], 'lessons_page');
        $session = $lesson ? $lessons->lesson($actor, $group, $lesson) : null;
        $marks = $session ? DB::table('academy_lesson_students as r')->join('users as u', 'u.id', '=', 'r.student_id')
            ->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'u.id')->whereNull('p.deleted_at'); })
            ->where('r.school_id', $actor->school_id)->where('r.lesson_id', $session->id)->orderBy('p.firstname')
            ->get(['r.*', 'p.firstname', 'p.lastname', 'u.name']) : collect();
        $candidates = null;
        if ((int) $actor->usergroup_id === 3 && !$session) {
            $candidates = app(LearningAccess::class)->students($actor)->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'users.id')->whereNull('p.deleted_at'); })
                ->leftJoin('academy_student_journeys as j', 'j.student_id', '=', 'users.id')->where('users.status', 'active')->whereNotIn('users.id', $roster->pluck('id'));
            if ($term = trim((string) $request->query('q', ''))) { $candidates->where(fn ($q) => $q->where('p.firstname', 'like', '%'.$term.'%')->orWhere('p.lastname', 'like', '%'.$term.'%')->orWhere('users.email', 'like', '%'.$term.'%')); }
            if (in_array($request->query('assignment'), ['assigned', 'unassigned'], true)) {
                $method = $request->query('assignment') === 'assigned' ? 'whereExists' : 'whereNotExists';
                $candidates->$method(fn ($q) => $q->selectRaw('1')->from('student_academics as active')->whereColumn('active.user_id', 'users.id')->where('active.school_id', $actor->school_id)->whereNull('active.deleted_at'));
            }
            $candidates->selectSub(DB::table('student_academics as current_en')->join('standards_link as current_class', 'current_class.id', '=', 'current_en.standardLink_id')
                ->join('sections as current_section', 'current_section.id', '=', 'current_class.section_id')->whereColumn('current_en.user_id', 'users.id')
                ->where('current_en.school_id', $actor->school_id)->whereNull('current_en.deleted_at')->orderByDesc('current_en.id')->limit(1)->select('current_section.name'), 'assigned_group');
            $candidates = $candidates->orderBy('p.firstname')->addSelect(['users.id', 'users.email', 'p.firstname', 'p.lastname', 'j.level', 'j.placement_status'])->paginate(25, ['*'], 'students_page')->withQueryString();
        }
        return view('academy.group', compact('record', 'roster', 'summaries', 'teacher', 'history', 'session', 'marks', 'candidates') + [
            'role' => (int) $actor->usergroup_id, 'layout' => (int) $actor->usergroup_id === 3 ? 'layouts.admin.layout' : 'layouts.teacher.layout']);
    }

    public function message(Request $request, int $student, AcademyLessons $lessons)
    {
        $lessons->message($request->user(), $student, $request->all());
        return back()->with('successmessage', 'Feedback message shared with the student and linked parents.');
    }

    public function addStudents(Request $request, int $group, AcademyLessons $lessons)
    {
        $lessons->addStudents($request->user(), $group, $request->all());
        return redirect('/learning/academy/groups/'.$group)->with('successmessage', 'Students added to this group. Previous main-group enrollments remain in history.');
    }

    public function start(Request $request, int $group, AcademyLessons $lessons)
    {
        \App\Support\AcademyClock::normalize($request, 'start_time');
        $lesson = $lessons->start($request->user(), $group, $request->all());
        return redirect('/learning/academy/groups/'.$group.'/lessons/'.$lesson);
    }

    public function save(Request $request, int $group, int $lesson, AcademyLessons $lessons)
    {
        $lessons->save($request->user(), $group, $lesson, $request->all());
        return back()->with('successmessage', 'Attendance and homework saved. Linked parents can see the updated record.');
    }
}
