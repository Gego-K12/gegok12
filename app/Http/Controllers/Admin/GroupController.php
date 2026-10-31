<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddGroupMembersRequest;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Services\AcademyGroups;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function store(GroupStoreRequest $request, AcademyGroups $groups)
    {
        $group = $groups->create((int) $request->user()->school_id, $request->validated());
        return response()->json(['status' => true, 'message' => 'Group created successfully', 'data' => $group]);
    }

    public function index($standardLinkId = null)
    {
        $groups = Group::forAcademy((int) auth()->user()->school_id);
        if ($standardLinkId !== null && $standardLinkId !== 'null') {
            $groups->where('standardLink_id', $standardLinkId);
        }
        return response()->json(['success' => true, 'message' => 'Group List',
            'data' => GroupResource::collection($groups->latest()->get())]);
    }

    public function list()
    {
        return response()->json(['data' => GroupResource::collection(
            Group::forAcademy((int) auth()->user()->school_id)->latest()->get()
        )]);
    }

    public function addMembers(AddGroupMembersRequest $request, AcademyGroups $groups)
    {
        $groups->enroll((int) $request->user()->school_id, (int) $request->group_id, $request->selectedUsers);
        return response()->json(['message' => 'Students added to group successfully']);
    }

    public function update(Request $request, int $id)
    {
        abort_unless((int) $request->user()->usergroup_id === 3, 403);
        $group = Group::forAcademy((int) $request->user()->school_id)->findOrFail($id);
        $data = $request->validate(['group_name' => ['required', 'string', 'max:255',
            Rule::unique('groups', 'group_name')->where('standardLink_id', $group->standardLink_id)->whereNull('deleted_at')->ignore($group->id)]]);
        $group->update($data);
        return response()->json(['status' => true, 'message' => 'Group updated successfully', 'data' => $group]);
    }

    public function destroy(Request $request, int $id)
    {
        abort_unless((int) $request->user()->usergroup_id === 3, 403);
        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
            $group = Group::forAcademy((int) $request->user()->school_id)->whereKey($id)->lockForUpdate()->firstOrFail();
            $group->members()->delete();
            $group->delete();
        });
        return response()->json(['status' => true, 'message' => 'Group archived successfully']);
    }

    public function members(int $id)
    {
        Group::forAcademy((int) auth()->user()->school_id)->findOrFail($id);
        return view('admin.groups.member-list', compact('id'));
    }

    public function showlist()
    {
        return view('admin.groups.list');
    }
}
