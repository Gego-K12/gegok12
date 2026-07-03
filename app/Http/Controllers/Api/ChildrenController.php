<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\UserDetail as UserDetailResource;
use App\Models\User;
use Illuminate\Http\Request;

class ChildrenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function listChildren(Request $request)
    {
        $parentUser = $request->user();
        $children = $parentUser->children->map(function ($item) {
            $child = User::find($item->student_id);

            return [
                'student_id' => $child->id,
                'name' => $child->FullName,
                'gender' => $child->userprofile->gender,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Children List',
            'data' => $children,
        ], 200);
    }

    public function countChildren(Request $request)
    {
        $parentUser = $request->user();
        $children = $parentUser->children->count();

        return response()->json([
            'success' => true,
            'message' => 'Children Count',
            'data' => $children,
        ], 200);
    }

    public function showChildren(Request $request, $id)
    {
        $children_id = $request->user()->children->pluck('student_id')->toArray();
        $children = User::where('id', $id)->whereIn('id', $children_id)->first();

        $children = new UserDetailResource($children);

        return response()->json([
            'success' => true,
            'message' => 'Children Details',
            'data' => $children,
        ], 200);
    }
}
