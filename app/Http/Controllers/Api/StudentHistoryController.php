<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StudentHistoryRequest;
use App\Models\StudentHistory;
use App\Traits\StudentHistoryProcess;
use Exception;
use Illuminate\Support\Facades\Auth;
use Log;

class StudentHistoryController extends Controller
{
    public function update(StudentHistoryRequest $request)
    {

        try {
            $array = [
                'image' => 'App\Models\Video',
                'video' => 'App\Models\Video',
                'homework' => 'App\Models\Homework',
                'assignment' => 'App\Models\Assignment',
            ];
            $school_id = Auth::user()->school_id;
            $student_id = $request->student_id;
            $parent_id = $request->parent_id;
            $entity_id = $request->module_id;
            $type = $request->type;
            $entity_name = $array[$type];

            $exists = StudentHistory::where([['school_id', $school_id], ['student_id', $student_id], ['entity_id', $entity_id], ['type', $type]])->exists();
            if (! $exists) {
                StudentHistoryProcess::createReadHistory($school_id, $student_id, $parent_id, $entity_id, $entity_name, $type);
                $message = 'Updated Successfully';
            } else {
                $message = 'Already Updated';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());

        }

    }
}
