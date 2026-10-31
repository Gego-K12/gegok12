<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherLearningGuard
{
    public function check(Request $request, object $actor): void
    {
        if (!config('academy_backend.enabled')) { return; }
        $assignments = app(AcademyAssignments::class);
        foreach (['standardLink_id', 'standards_link_id', 'standardLinkId'] as $key) {
            $id = $request->route($key) ?? $request->input($key);
            if ($id !== null && $id !== '') { abort_unless(is_numeric($id) && $assignments->allows($actor, (int) $id), 403); }
        }
        if ($request->is('teacher/standardLink/*') && $request->route('id') !== null) {
            abort_unless($assignments->allows($actor, (int) $request->route('id')), 403);
        }
        if ($request->is('teacher/attendance/*') && !$request->isMethod('GET')) {
            $request->validate(['absentCount' => 'nullable|integer|min:0|max:500', 'presentCount' => 'nullable|integer|min:0|max:500']);
            foreach (['absentCount' => 'user_id', 'presentCount' => 'present_id'] as $count => $prefix) {
                for ($i = 0; $i < (int) $request->input($count, 0); $i++) {
                    $id = $request->input($prefix.$i);
                    if ($id === 'false') { continue; }
                    $student = app(LearningAccess::class)->students($actor)->where('users.id', $id)->exists();
                    abort_unless($student, 403);
                    abort_unless(DB::table('student_academics')->where('school_id', $actor->school_id)->where('user_id', $id)
                        ->where('standardLink_id', $request->input('standardLink_id'))->whereNull('deleted_at')->exists(), 403);
                }
            }
        }
        // Detail endpoints include JSON subresources that did not previously use a gate.
        $name = $request->route('name');
        if ($name !== null && ($request->is('teacher/student/*') || $request->is('teacher/document/get/*'))) {
            $student = app(LearningAccess::class)->students($actor)->where('users.name', $name)->first(['users.id']);
            abort_unless($student, 404);
        }
    }
}
