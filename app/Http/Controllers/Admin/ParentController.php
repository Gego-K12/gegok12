<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParentAddRequest;
use App\Http\Requests\ParentUpdateRequest;
use App\Http\Resources\ActivityLog as ActivityLogResource;
use App\Http\Resources\Children as ChildrenResource;
use App\Http\Resources\Feedback as FeedbackResource;
use App\Http\Resources\User as UserResource;
use App\Models\ActivityLog;
use App\Models\Feedback;
use App\Models\StudentParentLink;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Userprofile;
use App\Models\Users\ParentUser;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use App\Traits\RegisterUser;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Log;

/**
 * Class ParentController
 *
 * Handles parent management including listing, creation,
 * viewing, editing, updating, deletion, and related resources
 * such as children, feedback, and activity logs.
 */
class ParentController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;
    use RegisterUser;

    /**
     * Get filtered list of parents.
     *
     * @return mixed
     */
    public function list(Request $request)
    {
        //
        return $this->ParentFilter($request, Auth::user()->school_id, 7);
    }

    /**
     * Display parent index page.
     *
     * @return View
     */
    public function index()
    {
        //
        $query = \Request::getQueryString();

        return view('/admin/parent/index', ['query' => $query]);
    }

    /**
     * Show parent creation form.
     *
     * @return View
     */
    public function create()
    {
        //
        $ref_name = Request('ref_name') ? Request('ref_name') : '';
        $count = User::where('school_id', Auth::user()->school_id)
            ->where('usergroup_id', 6)
            ->orWhere('usergroup_id', 7)
            ->count();

        $subscription = Subscription::where('school_id', Auth::user()->school_id)->first();

        return view('admin/parent/create', [
            'ref_name' => $ref_name,
            'count' => $count,
            'subscription' => $subscription,
        ]);
    }

    /**
     * Get parent add form related lists.
     *
     * @return array
     */
    public function addList(Request $request)
    {
        $array = [];

        $array['qualificationlist'] = SiteHelper::getQualifications();
        $array['standardLinklist'] = SiteHelper::getStandardLinkList(Auth::user()->school_id);

        if ($request->standardLink_id != null) {
            $parent = ParentUser::where('school_id', Auth::user()->school_id)
                ->ByRole(7)
                ->ByStandardLinkParentList($request->standardLink_id)
                ->get();

            $array['parent'] = UserResource::collection($parent);
        }

        return $array;
    }

    /**
     * Validate parent creation request.
     *
     * @return void
     */
    public function validationParent(ParentAddRequest $request)
    {
        //
    }

    /**
     * Store a newly created parent.
     *
     * @return mixed
     */
    public function store(ParentAddRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $student_id = '';

            $user = $this->CreateParent($student_id, $request, $school_id, 7);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_PARENT,
                trans('messages.add_success_msg', ['module' => 'Parent'])
            );

            if ($request->parent == 'add') {
                \Session::put('successmessage', trans('messages.add_success_msg', ['module' => 'Parent']));

                return redirect()->back();
            } else {
                $res['success'] = trans('messages.add_success_msg', ['module' => 'Parent']);

                return $res;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display parent details.
     *
     * @param  string  $name
     * @return View
     */
    public function show($name)
    {
        //
        $user = ParentUser::where('name', $name)->first();
        $userprofile = Userprofile::where('user_id', $user->id)->first();
        $parentprofile = $user->getParentDetails();

        return view('/admin/parent/show', [
            'user' => $user,
            'userprofile' => $userprofile,
            'parentprofile' => $parentprofile,
        ]);
    }

    /**
     * Display children of a parent.
     *
     * @param  string  $name
     * @return AnonymousResourceCollection
     */
    public function showChildren($name)
    {
        //
        $parent = ParentUser::where('name', $name)->first();

        return ChildrenResource::collection($parent->children);
    }

    /**
     * Display feedback conversations for a parent.
     *
     * @param  string  $name
     * @return AnonymousResourceCollection
     */
    public function showFeedbacks($name)
    {
        //
        $parent = ParentUser::where('name', $name)->first();
        $conversation = Feedback::where('parent_id', $parent->id)->get();

        return FeedbackResource::collection($conversation);
    }

    /**
     * Display activity logs of a parent.
     *
     * @param  string  $name
     * @return AnonymousResourceCollection
     */
    public function showActivityLog($name)
    {
        //
        $user = ParentUser::with('userprofile')->where('name', $name)->first();

        if (Gate::allows('member', $user)) {
            $activitylog = ActivityLog::where('subject_id', $user->id)->paginate(5);

            return ActivityLogResource::collection($activitylog);
        } else {
            abort(403);
        }
    }

    /**
     * Get parent data for edit form.
     *
     * @param  string  $name
     * @return array
     */
    public function editList($name)
    {
        //
        $user = ParentUser::where('name', $name)->first();
        $userprofile = Userprofile::where('user_id', $user->id)->first();
        $parentprofile = $user->getParentDetails();

        $array = [];

        $array['firstname'] = $userprofile->firstname;
        $array['lastname'] = $userprofile->lastname;
        $array['alternate_no'] = $userprofile->alternate_no ?? '';
        $array['profession'] = $parentprofile['profession'] ?? '';
        $array['sub_occupation'] = $parentprofile['sub_occupation'] ?? '';
        $array['designation'] = $parentprofile['designation'] ?? '';
        $array['organization_name'] = $parentprofile['organization_name'] ?? '';
        $array['official_address'] = $parentprofile['official_address'] ?? '';
        $array['annual_income'] = $parentprofile['annual_income'] ?? '';
        $array['relation'] = $parentprofile['relation'] ?? '';
        $array['qualification_id'] = $parentprofile['qualification_id'] ?? '';
        $array['qualification_name'] = $parentprofile['qualification_name'] ?? '';
        $array['qualificationlist'] = SiteHelper::getQualifications();

        return $array;
    }

    /**
     * Show parent edit form.
     *
     * @param  string  $name
     * @return View
     */
    public function edit($name)
    {
        //
        $ref_name = Request('ref_name') ? Request('ref_name') : '';
        $user = ParentUser::where('name', $name)->first();
        $userprofile = Userprofile::where('user_id', $user->id)->first();
        $parentprofile = $user->getParentDetails();

        return view('/admin/parent/edit', [
            'ref_name' => $ref_name,
            'user' => $user,
            'userprofile' => $userprofile,
            'parentprofile' => $parentprofile,
        ]);
    }

    /**
     * Validate parent update request.
     *
     * @param  string  $name
     * @return void
     */
    public function editValidationUser(ParentUpdateRequest $request, $name)
    {
        //
    }

    /**
     * Update parent details.
     *
     * @param  string  $name
     * @return RedirectResponse
     */
    public function update(ParentUpdateRequest $request, $name)
    {
        //
        try {
            $user = ParentUser::where('name', $name)->first();
            $school_id = Auth::user()->school_id;

            $userprofile = $this->UpdateParent('', $request, $school_id, $user->id);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_PARENT,
                trans('messages.update_success_msg', ['module' => 'Parent'])
            );

            \Session::put('successmessage', trans('messages.update_success_msg', ['module' => 'Parent']));

            return redirect()->back();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Delete a parent.
     *
     * @param  string  $name
     * @return RedirectResponse
     */
    public function destroy($name)
    {
        \DB::beginTransaction();

        try {
            $user = ParentUser::where('name', $name)->first();
            $studentparentlink = StudentParentLink::where('parent_id', $user->id)->first();

            if ($studentparentlink) {
                $studentparentlink->delete();
            }

            $userprofile = Userprofile::where('user_id', $user->id)->first();
            $userprofile->delete();
            $user->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Parent']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_PARENT,
                $message
            );

            \Session::put('successmessage', $message);
            \DB::commit();

            return redirect('/admin/parents');
        } catch (Exception $e) {
            \DB::rollBack();
        }
    }
}
