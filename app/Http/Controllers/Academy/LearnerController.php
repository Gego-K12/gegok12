<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\AcademyAssignments;
use App\Services\AcademyContext;
use App\Services\AcademyGroups;
use App\Services\AcademyStudents;
use App\Services\LearningAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LearnerController extends Controller
{
    private function layout(object $actor): string
    {
        return match ((int) $actor->usergroup_id) {
            3 => 'layouts.admin.layout', 5 => 'layouts.teacher.layout',
            6 => 'layouts.student.layout', 7 => 'academy.parent-layout', default => abort(403),
        };
    }

    public function index(Request $request, LearningAccess $access)
    {
        $actor = $request->user();
        if ((int) $actor->usergroup_id === 5) { return redirect('/learning/academy/groups'); }
        $q = $access->students($actor)->leftJoin('userprofiles as p', function ($j) {
            $j->on('p.user_id', '=', 'users.id')->whereNull('p.deleted_at');
        })->leftJoin('academy_student_journeys as j', 'j.student_id', '=', 'users.id')
            ->leftJoin('student_academics as en', function ($join) use ($actor) {
                $join->on('en.user_id', '=', 'users.id')->whereNull('en.deleted_at')
                    ->where('en.school_id', $actor->school_id)
                    ->whereRaw('en.id = (select max(current_en.id) from student_academics current_en where current_en.user_id = users.id and current_en.school_id = users.school_id and current_en.deleted_at is null)');
            })->leftJoin('standards_link as c', 'c.id', '=', 'en.standardLink_id')
            ->leftJoin('groups as current_group', function ($join) {
                $join->on('current_group.standardLink_id', '=', 'c.id')->whereNull('current_group.deleted_at')
                    ->where('current_group.id', '=', function ($q) { $q->selectRaw('min(cg.id)')->from('groups as cg')->whereColumn('cg.standardLink_id', 'c.id')->whereNull('cg.deleted_at'); });
            })->leftJoin('academy_group_settings as current_settings', 'current_settings.group_id', '=', 'current_group.id')
            ->leftJoin('sections as section', 'section.id', '=', 'c.section_id')
            ->leftJoin('userprofiles as teacher', function ($j) { $j->on('teacher.user_id', '=', 'c.class_teacher_id')->whereNull('teacher.deleted_at'); });
        // Qualified columns keep the original LearningAccess tenant policy intact after joins.
        if ($search = trim((string) $request->query('q', ''))) {
            $q->where(function ($s) use ($search) {
                $s->where('users.email', 'like', '%'.$search.'%')->orWhere('p.firstname', 'like', '%'.$search.'%')->orWhere('p.lastname', 'like', '%'.$search.'%');
            });
        }
        if ($request->query('assignment') === 'assigned') { $q->whereNotNull('en.standardLink_id'); }
        if ($request->query('assignment') === 'unassigned') { $q->whereNull('en.standardLink_id'); }
        if ($request->filled('group')) { $q->where('en.standardLink_id', (int) $request->query('group')); }
        if ($request->query('stage') === 'placement') { $q->where(fn ($s) => $s->whereNull('j.id')->orWhereIn('j.placement_status', ['pending', 'scheduled'])); }
        if ($request->query('stage') === 'group') { $q->whereIn('j.placement_status', ['completed', 'waived'])->whereNull('j.completed_at')->where(fn ($s) => $s->whereNull('en.id')->orWhereNull('current_settings.level')->orWhereColumn('j.level', '!=', 'current_settings.level')); }
        if ($request->query('stage') === 'completed') { $q->whereNotNull('j.completed_at'); }
        $students = $q->orderBy('p.firstname')->orderBy('users.id')->paginate(25, [
            'users.id', 'users.name', 'users.email', 'p.firstname', 'p.lastname', 'j.placement_status', 'j.level', 'j.unit', 'j.completed_at',
            'current_group.id as group_id', 'current_settings.meeting_days', 'current_settings.start_time', 'current_settings.end_time', 'current_settings.level as group_level', 'current_group.group_name as group_name', 'teacher.firstname as teacher_name', 'c.class_teacher_id', 'en.standardLink_id',
        ])->withQueryString();
        $summaries = app(\App\Services\AcademyLessons::class)->summary((int) $actor->school_id, $students->pluck('id')->all());
        return view('academy.learners', ['students' => $students, 'summaries' => $summaries, 'layout' => $this->layout($actor), 'role' => (int) $actor->usergroup_id]);
    }

    public function create(Request $request, string $type)
    {
        app(AcademyStudents::class)->admin($request->user());
        abort_if(config('academy_backend.test_only'), 403, 'Test-only mode uses the four prepared accounts. Disable it to register additional accounts.');
        abort_unless(in_array($type, ['student', 'parent', 'teacher'], true), 404);
        return view('academy.register', ['type' => $type, 'layout' => $this->layout($request->user())]);
    }

    public function store(Request $request, string $type, AcademyStudents $students)
    {
        abort_if(config('academy_backend.test_only'), 403, 'Disable test-only mode to register additional accounts.');
        $roles = ['student' => 6, 'parent' => 7, 'teacher' => 5];
        abort_unless(isset($roles[$type]), 404);
        $id = $students->register($request->user(), $request->all(), $roles[$type]);
        return redirect($type === 'student' ? '/learning/academy/students/'.$id : '/learning/academy')->with('successmessage', ucfirst($type).' account created.');
    }

    public function show(Request $request, int $student, LearningAccess $access, AcademyStudents $students)
    {
        $actor = $request->user(); $person = $access->student($actor, $student);
        $journey = $students->journey($actor, $student);
        $profile = DB::table('userprofiles')->where('user_id', $student)->where('school_id', $actor->school_id)->whereNull('deleted_at')->first();
        $account = (int) $actor->usergroup_id === 3 ? DB::table('users')->where('id', $student)->first(['email', 'mobile_no']) : null;
        $enrollments = DB::table('student_academics as a')->join('standards_link as c', 'c.id', '=', 'a.standardLink_id')
            ->join('sections as s', 's.id', '=', 'c.section_id')->leftJoin('userprofiles as t', function ($j) { $j->on('t.user_id', '=', 'c.class_teacher_id')->whereNull('t.deleted_at'); })
            ->where('a.school_id', $actor->school_id)->where('a.user_id', $student)->orderByDesc('a.id')
            ->get(['a.id', 'a.standardLink_id', 'a.deleted_at', 'a.created_at', 's.name as group_name', 't.firstname as teacher_name', 'c.class_teacher_id']);
        $groups = DB::table('groups as g')->join('standards_link as c', 'c.id', '=', 'g.standardLink_id')
            ->leftJoin('academy_group_settings as settings', 'settings.group_id', '=', 'g.id')
            ->where('c.school_id', $actor->school_id)->where('c.status', 1)->whereNull('c.deleted_at')->whereNull('g.deleted_at');
        if ((int) $actor->usergroup_id !== 3) {
            $groups->whereIn('g.standardLink_id', $enrollments->whereNull('deleted_at')->pluck('standardLink_id'));
        }
        $groups = $groups->orderBy('g.group_name')->get(['g.id', 'g.group_name', 'settings.level', 'settings.schedule', 'settings.meeting_days', 'settings.start_time', 'settings.end_time']);
        $parents = DB::table('student_parent_links as link')->join('users as u', 'u.id', '=', 'link.parent_id')
            ->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'u.id')->whereNull('p.deleted_at'); })
            ->where('link.school_id', $actor->school_id)->where('link.student_id', $student)->whereNull('link.deleted_at')->whereNull('u.deleted_at')
            ->get(['u.id', 'u.email', 'p.firstname', 'p.lastname']);
        $parentOptions = (int) $actor->usergroup_id === 3 ? DB::table('users')->where('school_id', $actor->school_id)->where('usergroup_id', 7)->where('status', 'active')->whereNull('deleted_at')->when(config('academy_backend.test_only'), fn ($q) => $q->where('email', config('academy_backend.demo_emails.7')))->orderBy('email')->get(['id', 'email']) : collect();
        $feedback = DB::table('academy_teacher_feedback as f')->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'f.teacher_id')->whereNull('p.deleted_at'); })
            ->where('f.school_id', $actor->school_id)->where('f.student_id', $student)->orderByDesc('f.id')
            ->paginate(15, ['f.*', 'p.firstname as teacher_name'], 'feedback_page');
        $events = (int) $actor->usergroup_id === 3 ? DB::table('academy_learning_events')->where('school_id', $actor->school_id)->where('student_id', $student)->orderByDesc('id')->limit(30)->get() : collect();
        $attendance = DB::table('attendances')->where('school_id', $actor->school_id)->where('user_id', $student)->whereNull('deleted_at')->orderByDesc('date')->limit(15)->get(['date', 'session', 'status']);
        $homework = app(\App\Http\Controllers\LearningController::class)->homework($request, $student, $access)->getData(true);
        $teacherMessages = DB::table('academy_student_messages as m')->leftJoin('userprofiles as t', function ($j) { $j->on('t.user_id', '=', 'm.teacher_id')->whereNull('t.deleted_at'); })
            ->where('m.school_id', $actor->school_id)->where('m.student_id', $student)->orderByDesc('m.id')
            ->paginate(15, ['m.message', 'm.created_at', 't.firstname as teacher_name'], 'messages_page');
        $lessonSummary = app(\App\Services\AcademyLessons::class)->summary((int) $actor->school_id, [$student])->get($student);
        $lessonRecords = DB::table('academy_lesson_students as r')->join('academy_group_lessons as l', 'l.id', '=', 'r.lesson_id')
            ->join('groups as g', 'g.id', '=', 'l.group_id')->where('r.school_id', $actor->school_id)->where('r.student_id', $student)
            ->orderByDesc('l.lesson_date')->orderByDesc('l.start_time')->paginate(20, ['r.attendance', 'r.homework', 'l.lesson_date', 'l.start_time', 'l.topic', 'g.group_name'], 'records_page');
        return view('academy.student', compact('teacherMessages', 'lessonSummary', 'lessonRecords', 'person', 'profile', 'account', 'journey', 'enrollments', 'groups', 'parents', 'parentOptions', 'feedback', 'events', 'attendance', 'homework') + ['layout' => $this->layout($actor), 'role' => (int) $actor->usergroup_id]);
    }

    public function updateProfile(Request $request, int $student, AcademyStudents $students)
    {
        $students->updateProfile($request->user(), $student, $request->all());
        return back()->with('successmessage', 'Student details saved.');
    }

    public function unlinkParent(Request $request, int $student, AcademyStudents $students)
    {
        $data = $request->validate(['parent_id' => 'required|integer']);
        $students->unlinkParent($request->user(), $student, (int) $data['parent_id']);
        return back()->with('successmessage', 'Parent access to this child removed.');
    }

    public function placement(Request $request, int $student, AcademyStudents $students)
    {
        $students->placement($request->user(), $student, $request->all());
        return back()->with('successmessage', 'Placement saved.');
    }

    public function assign(Request $request, int $student, AcademyStudents $students)
    {
        $data = $request->validate(['group_id' => 'required|integer']);
        $students->assign($request->user(), $student, (int) $data['group_id']);
        return back()->with('successmessage', 'Student assigned. Learning records and group membership updated together.');
    }

    public function parent(Request $request, int $student, AcademyStudents $students)
    {
        $data = $request->validate(['parent_id' => 'required|integer']);
        $students->linkParent($request->user(), $student, (int) $data['parent_id']);
        return back()->with('successmessage', 'Parent linked. The child is now visible in their parent account.');
    }

    public function feedback(Request $request, int $student, AcademyStudents $students)
    {
        $students->feedback($request->user(), $student, $request->all());
        return back()->with('successmessage', 'Feedback shared with the student and linked parents.');
    }

    public function readFeedback(Request $request, int $student, int $feedback, LearningAccess $access)
    {
        abort_unless((int) $request->user()->usergroup_id === 7, 403);
        $access->student($request->user(), $student);
        DB::table('academy_teacher_feedback')->where('school_id', $request->user()->school_id)->where('student_id', $student)->where('id', $feedback)->whereNull('parent_read_at')->update(['parent_read_at' => now()]);
        return back()->with('successmessage', 'Feedback marked as read.');
    }

    public function groups(Request $request)
    {
        $actor = $request->user(); abort_unless(in_array((int) $actor->usergroup_id, [3, 5], true), 403);
        $query = DB::table('groups as g')->join('standards_link as c', 'c.id', '=', 'g.standardLink_id')
            ->leftJoin('academy_group_settings as settings', 'settings.group_id', '=', 'g.id')
            ->leftJoin('userprofiles as teacher', function ($j) { $j->on('teacher.user_id', '=', 'c.class_teacher_id')->whereNull('teacher.deleted_at'); })
            ->where('c.school_id', $actor->school_id)->where('c.status', 1)->whereNull('c.deleted_at')->whereNull('g.deleted_at')
            ->where('c.academic_year_id', app(AcademyContext::class)->period((int) $actor->school_id)->id);
        if (config('academy_backend.test_only')) { $query->where('settings.is_demo', 1); }
        if ((int) $actor->usergroup_id === 5) { $query->whereIn('c.id', app(AcademyAssignments::class)->classIds($actor)); }
        $filters = $request->validate(['level' => ['nullable', Rule::in(['1','2','3','4','5','6','unconfigured'])], 'teacher_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('school_id', $actor->school_id)->where('usergroup_id', 5)->whereNull('deleted_at')]]);
        $filtersReady = (int) $actor->usergroup_id === 5 || (!empty($filters['level']) && !empty($filters['teacher_id']));
        if (!$filtersReady) { $query->whereRaw('1 = 0'); }
        if (($filters['level'] ?? '') === 'unconfigured') { $query->whereNull('settings.level'); }
        elseif (!empty($filters['level'])) { $query->where('settings.level', $filters['level']); }
        if ((int) $actor->usergroup_id === 3 && !empty($filters['teacher_id'])) { $query->where('c.class_teacher_id', $filters['teacher_id']); }
        $groups = $query->orderBy('g.group_name')->paginate(25, ['g.id', 'g.group_name', 'g.standardLink_id', 'c.class_teacher_id', 'settings.level', 'settings.schedule', 'settings.meeting_days', 'settings.start_time', 'settings.end_time', 'teacher.firstname as teacher_name'])->withQueryString();
        $teachers = (int) $actor->usergroup_id === 3 ? DB::table('users as u')->leftJoin('userprofiles as p', function ($j) { $j->on('p.user_id', '=', 'u.id')->whereNull('p.deleted_at'); })
            ->where('u.school_id', $actor->school_id)->where('u.usergroup_id', 5)->where('u.status', 'active')->whereNull('u.deleted_at')->when(config('academy_backend.test_only'), fn ($q) => $q->where('u.email', config('academy_backend.demo_emails.5')))->orderBy('p.firstname')->get(['u.id', 'u.email', 'p.firstname', 'p.lastname']) : collect();
        $groupStudents = app(\App\Services\AcademyLessons::class)->rosterPreview($actor, $groups->pluck('id')->all());
        return view('academy.groups', compact('groups', 'teachers', 'groupStudents', 'filtersReady') + ['layout' => $this->layout($actor), 'role' => (int) $actor->usergroup_id]);
    }

    public function saveGroup(Request $request, ?int $group = null)
    {
        $actor = $request->user(); $students = app(AcademyStudents::class); $students->admin($actor);
        \App\Support\AcademyClock::normalize($request, 'start_time');
        \App\Support\AcademyClock::normalize($request, 'end_time');
        $data = $request->validate(['group_name' => 'required|string|max:100', 'level' => 'required|integer|between:1,6',
            'teacher_id' => ['required', Rule::exists('users', 'id')->where('school_id', $actor->school_id)->where('usergroup_id', 5)->where('status', 'active')->whereNull('deleted_at')], 'schedule' => 'nullable|string|max:500',
            'structured_schedule' => 'nullable|boolean', 'meeting_days' => 'required_if:structured_schedule,1|nullable|array|min:1|max:7',
            'meeting_days.*' => ['required', 'distinct', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'start_time' => 'required_if:structured_schedule,1|required_with:meeting_days|nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time']);
        DB::transaction(function () use ($actor, $data, $group, $students) {
            DB::table('schools')->where('id', $actor->school_id)->lockForUpdate()->first();
            if ($group) {
                $record = Group::forAcademy((int) $actor->school_id)->whereKey($group)->lockForUpdate()->firstOrFail();
                $other = Group::forAcademy((int) $actor->school_id)->where('group_name', $data['group_name'])->whereKeyNot($record->id)->exists();
                if ($other) { throw ValidationException::withMessages(['group_name' => 'A group with this name already exists.']); }
                $link = DB::table('standards_link')->where('id', $record->standardLink_id)->first();
                $mismatch = DB::table('student_academics as a')->join('academy_student_journeys as j', 'j.student_id', '=', 'a.user_id')
                    ->where('a.standardLink_id', $link->id)->whereNull('a.deleted_at')->where('j.level', '!=', $data['level'])->exists();
                if ($mismatch) { throw ValidationException::withMessages(['level' => 'Move existing students to a suitable group before changing its level.']); }
                $record->update(['group_name' => $data['group_name']]);
                $otherLevel = DB::table('academy_group_settings as s')->join('groups as g', 'g.id', '=', 's.group_id')
                    ->where('g.standardLink_id', $link->id)->where('g.id', '!=', $record->id)->whereNull('g.deleted_at')->where('s.level', '!=', $data['level'])->exists();
                if ($otherLevel) { throw ValidationException::withMessages(['level' => 'Groups sharing a classroom must use the same course level.']); }
                $slug = 'academy-'.$actor->school_id.'-level-'.$data['level'];
                $standardId = DB::table('standards')->where('school_id', $actor->school_id)->where('slug', $slug)->whereNull('deleted_at')->value('id');
                if (!$standardId) {
                    $standardId = DB::table('standards')->insertGetId(['school_id' => $actor->school_id, 'slug' => $slug,
                        'name' => 'Level '.$data['level'], 'order' => $data['level'], 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
                }
                // Keep original class labels, unit subjects and teacher assignments connected.
                $sectionId = DB::table('sections')->where('school_id', $actor->school_id)->where('name', $data['group_name'])->whereNull('deleted_at')->value('id');
                if (!$sectionId) {
                    $sectionId = DB::table('sections')->insertGetId(['school_id' => $actor->school_id, 'name' => $data['group_name'], 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
                }
                DB::table('standards_link')->where('id', $link->id)->update(['standard_id' => $standardId, 'section_id' => $sectionId, 'class_teacher_id' => $data['teacher_id'], 'updated_at' => now()]);
                DB::table('class_teacher_links')->where('standardLink_id', $link->id)->where('school_id', $actor->school_id)->whereNull('deleted_at')
                    ->update(['deleted_at' => now(), 'updated_at' => now()]);
                foreach ([1, 2] as $unit) {
                    $subjectId = DB::table('subjects')->where('school_id', $actor->school_id)->where('academic_year_id', $link->academic_year_id)
                        ->where('standard_id', $standardId)->where('section_id', $sectionId)->where('name', 'Unit '.$unit)->whereNull('deleted_at')->value('id');
                    if (!$subjectId) {
                        $subjectId = DB::table('subjects')->insertGetId(['school_id' => $actor->school_id, 'academic_year_id' => $link->academic_year_id,
                            'standard_id' => $standardId, 'section_id' => $sectionId, 'name' => 'Unit '.$unit, 'type' => 'core', 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
                    }
                    DB::table('class_teacher_links')->insert(['school_id' => $actor->school_id, 'academic_year_id' => $link->academic_year_id,
                        'standardLink_id' => $link->id, 'teacher_id' => $data['teacher_id'], 'subject_id' => $subjectId, 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
                }
            } else { $record = app(AcademyGroups::class)->create((int) $actor->school_id, $data); }
            DB::table('academy_group_settings')->updateOrInsert(['group_id' => $record->id], ['school_id' => $actor->school_id, 'level' => $data['level'], 'schedule' => $data['schedule'] ?? null, 'created_at' => now(), 'updated_at' => now()]);
            if (config('academy_backend.test_only')) { DB::table('academy_group_settings')->where('group_id', $record->id)->update(['is_demo' => 1]); }
            $calendar = array_intersect_key($data, array_flip(['meeting_days', 'start_time', 'end_time']));
            if (array_key_exists('meeting_days', $calendar)) { $calendar['meeting_days'] = json_encode($calendar['meeting_days'] ?? []); }
            if ($calendar) { DB::table('academy_group_settings')->where('group_id', $record->id)->update($calendar + ['timezone' => 'Africa/Cairo']); }
            $students->log($actor, null, $group ? 'group_updated' : 'group_created', ['group_id' => $record->id, 'name' => $data['group_name'], 'level' => $data['level'], 'teacher_id' => $data['teacher_id']]);
            $period = app(AcademyContext::class)->period((int) $actor->school_id);
            DB::afterCommit(fn () => Cache::forget('standardLink'.$actor->school_id.':'.$period->id));
        });
        return back()->with('successmessage', 'Group saved.');
    }

    public function archiveGroup(Request $request, int $group)
    {
        $actor = $request->user(); $students = app(AcademyStudents::class); $students->admin($actor);
        DB::transaction(function () use ($actor, $group, $students) {
            $record = Group::forAcademy((int) $actor->school_id)->whereKey($group)->lockForUpdate()->firstOrFail();
            if (DB::table('student_academics')->where('standardLink_id', $record->standardLink_id)->whereNull('deleted_at')->exists()) {
                throw ValidationException::withMessages(['group' => 'Move the enrolled students before archiving this group.']);
            }
            DB::table('group_members')->where('group_id', $record->id)->whereNull('deleted_at')->update(['deleted_at' => now(), 'updated_at' => now()]);
            $record->delete();
            if (!Group::where('standardLink_id', $record->standardLink_id)->exists()) {
                DB::table('standards_link')->where('id', $record->standardLink_id)->update(['status' => 0, 'updated_at' => now()]);
                DB::table('class_teacher_links')->where('standardLink_id', $record->standardLink_id)->update(['status' => 0, 'updated_at' => now()]);
            }
            $students->log($actor, null, 'group_archived', ['group_id' => $group]);
        });
        return back()->with('successmessage', 'Group archived. Historical records retained.');
    }

    public function activity(Request $request, AcademyStudents $students)
    {
        $students->admin($request->user());
        $events = DB::table('academy_learning_events as e')->leftJoin('userprofiles as actor', function ($j) { $j->on('actor.user_id', '=', 'e.actor_id')->whereNull('actor.deleted_at'); })
            ->where('e.school_id', $request->user()->school_id)->orderByDesc('e.id')->paginate(30, ['e.*', 'actor.firstname as actor_name']);
        return view('academy.activity', ['events' => $events, 'layout' => $this->layout($request->user())]);
    }
}
