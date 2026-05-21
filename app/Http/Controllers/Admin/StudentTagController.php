<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentAcademic;
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
        $student = StudentAcademic::findOrFail($id);

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

        $student = StudentAcademic::findOrFail($id);

        $student->syncTagsWithType($request->tags ?? [], 'student');

        return response()->json([
            'message' => 'Student tags updated successfully',
        ]);
    }
}