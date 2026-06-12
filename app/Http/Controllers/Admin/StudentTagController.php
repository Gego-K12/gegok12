<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentTagRequest;
use Illuminate\Http\Request;
use App\Models\StudentAcademic;
use App\Models\Users\StudentUser;
use App\Models\SpatieTag;

class StudentTagController extends Controller
{
    public function index()
    {
        $tags = SpatieTag::where('type', 'student')
            ->orderBy('order_column')
            ->get();

        return response()->json([
            'tags' => $tags,
        ]);
    }

    public function show($id)
    {
        $student = StudentUser::findOrFail($id);

        return response()->json([
            'tags' => $student->tags->pluck('tag_name')->values(),
        ]);
    }

    public function sync(Request $request, $id)
    {
        $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'string',
        ]);

        $user = StudentUser::findOrFail($id);

        $user->syncTagsWithType($request->tags ?? [], 'student');

        return response()->json([
            'message' => 'Student tags updated successfully',
        ]);
    }
    public function addStudents(StudentTagRequest $request)
    {

        $tag = SpatieTag::findOrCreate(
            trim($request->tag_name),
            'student'
        );

        foreach ($request->selectedUsers as $studentId) {

            $student = StudentUser::find($studentId);

            if ($student && !$student->tags()->where('tags.id', $tag->id)->exists()) {
                $student->attachTag($tag);
            }
        }

        return response()->json([
            'message' => 'Tag assigned successfully'
        ]);
    }

}