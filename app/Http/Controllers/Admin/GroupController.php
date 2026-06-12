<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AddGroupMembersRequest;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Resources\GroupResource;
use App\Models\GroupMember;
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
    public function index($standardLinkId = null)
    {

        // dd($standardLinkId);
        $groups = Group::query();

        if (!empty($standardLinkId) && $standardLinkId !== 'null') {
            $groups->where('standardLink_id', $standardLinkId);
        }

        $groups = $groups->latest()->get();


        return response()->json([
            'success' => true,
            'message' => 'Group List',
            'data'    => GroupResource::collection($groups)
        ], 200);
    }
    public function list()
    {
        $groups = Group::get();

        return response()->json([
            'data'    => GroupResource::collection($groups)
        ]);
    }

    public function addMembers(AddGroupMembersRequest $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'selectedUsers' => 'required|array',
        ]);

        foreach ($request->selectedUsers as $studentId)
        {
            GroupMember::create(
            [
                'group_id'  => $request->group_id,
                'member_id' => $studentId,
                'member_type' => 'student',
            ]);
        }

        return response()->json([
            'message' => 'Students added to group successfully'
        ]);
    }
    public function showlist()
    {
        return view('admin.groups.list');
    }

}
