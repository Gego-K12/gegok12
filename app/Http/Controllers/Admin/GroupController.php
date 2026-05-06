<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GroupStoreRequest;
use App\Models\Group;

class GroupController extends Controller
{
    public function store(GroupStoreRequest $request)
    {
        $group = Group::create([
            'group_name' => $request->group_name,
            'standardLink_id' => $request->standards_link_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Group created successfully',
            'data' => $group
        ]);
    }
}
