<?php

namespace App\Http\Controllers;

use App\Services\LearningAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Read APIs for the four existing account roles; no new frontend or impersonation. */
class LearningController extends Controller
{
    public function students(Request $request, LearningAccess $access)
    {
        return response()->json($access->students($request->user())->orderBy('name')->paginate(20, ['id', 'name']));
    }

    public function show(Request $request, int $student, LearningAccess $access)
    {
        $person = $access->student($request->user(), $student);
        $enrollment = DB::table('student_academics')->where('school_id', $request->user()->school_id)
            ->where('user_id', $student)->whereNull('deleted_at')->orderByDesc('id')
            ->first(['id', 'academic_year_id', 'standardLink_id']);
        return response()->json(['student' => $person, 'enrollment' => $enrollment]);
    }

    public function homework(Request $request, int $student, LearningAccess $access)
    {
        $access->student($request->user(), $student);
        $enrollment = DB::table('student_academics')->where('school_id', $request->user()->school_id)
            ->where('user_id', $student)->whereNull('deleted_at')->orderByDesc('id')->first();
        $homework = DB::table('homeworks as h')->where('h.school_id', $request->user()->school_id)->whereNull('h.deleted_at');
        if ($enrollment && $enrollment->standardLink_id) {
            $homework->where('h.standardLink_id', $enrollment->standardLink_id)->where('h.academic_year_id', $enrollment->academic_year_id);
        } else {
            $homework->whereRaw('1 = 0');
        }
        $homework->whereExists(fn ($q) => $q->selectRaw('1')->from('homework_approvals as approval')
            ->whereColumn('approval.homework_id', 'h.id')->where('approval.status', 'approved')->whereNull('approval.deleted_at'));
        return response()->json($homework->orderByDesc('h.date')->paginate(20, ['h.id', 'h.description', 'h.date', 'h.subject_id']));
    }

    public function attendance(Request $request, int $student, LearningAccess $access)
    {
        $access->student($request->user(), $student);
        return response()->json(DB::table('attendances')->where('school_id', $request->user()->school_id)
            ->where('user_id', $student)->whereNull('deleted_at')->orderByDesc('date')
            ->paginate(30, ['id', 'date', 'session', 'status']));
    }
}
