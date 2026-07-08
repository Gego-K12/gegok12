<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\SinglePushEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Fee as FeeResource;
use App\Http\Resources\FeeGroup as FeeGroupResource;
use App\Http\Resources\FeeList as FeeListResource;
use App\Http\Resources\ShowEvent as ShowEventResource;
use App\Http\Resources\Task as TaskResource;
use App\Http\Resources\UnpaidFees as UnpaidFeesResource;
use App\Models\Events;
use App\Models\Fee;
use App\Models\FeeGroup;
use App\Models\FeePayment;
use App\Models\StandardLink;
use App\Models\User;
use App\Services\DashboardReaderService;
use App\Traits\Common;
use App\Traits\Dashboard;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class DashboardController
 *
 * Controller for admin dashboard operations: viewing summaries,
 * fetching tasks, events and unpaid fees related endpoints.
 */
class DashboardController extends Controller
{
    use Common;
    use Dashboard;
    use LogActivity;

    public function __construct(protected DashboardReaderService $dashboardReader) {}

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $admin_id = Auth::id();
        $school_id = Auth::user()->school_id;

        $dashboard = $this->adminDashboard($school_id, $admin_id);

        $standardLink = StandardLink::where('id', $request->standardLink_id)->first();

        $selected_teacher = User::where('id', $request->teacher_id)->first();

        return view('/admin/dashboard/dashboard', ['dashboard' => $dashboard, 'standardLink' => $standardLink, 'selected_teacher' => $selected_teacher]);
    }

    /**
     * Return task list for the authenticated user filtered by flag.
     *
     * @param  string|int  $task_flag
     * @return AnonymousResourceCollection
     */
    public function list(Request $request, $task_flag)
    {
        $tasks = $this->dashboardReader->taskWidgetList(Auth::user()->school_id, Auth::id(), $task_flag, $request->q);

        return TaskResource::collection($tasks);
    }

    /**
     * Return counts of tasks grouped by flag for the authenticated user.
     *
     * @return array
     */
    public function listCount()
    {
        return $this->dashboardReader->taskWidgetCounts(Auth::user()->school_id, Auth::id());
    }

    /**
     * Return upcoming events for the school.
     *
     * @return AnonymousResourceCollection
     */
    public function event()
    {
        $now = date('Y-m-d H:i:s');

        $event = Events::where('school_id', Auth::user()->school_id)->where('start_date', '>=', $now)->orderBy('start_date', 'asc')->take(5)->get();
        $event = ShowEventResource::collection($event);

        return $event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function structuralList()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $feelists = FeeGroup::where('school_id', $school_id)->whereHas('fee', function ($query) use ($school_id, $academic_year) {
            $query->where([['school_id', $school_id], ['academic_year_id', $academic_year->id]]); // ,['fee_type','structural']
        })->get();

        $feelists = FeeGroupResource::collection($feelists);

        return $feelists;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showStructuralList(Request $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);
            $feelists = FeeGroup::where('school_id', $school_id)->whereIn('id', $request->feegroup)->whereHas('fee', function ($query) use ($school_id, $academic_year) {
                $query->where([['school_id', $school_id], ['academic_year_id', $academic_year->id]]); // ,['fee_type','structural']
            })->get();
            \Session::put('amount', 0);

            $feelists = FeeListResource::collection($feelists);

            return $feelists;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function unpaidList($status)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $fees = Fee::where([['school_id', $school_id], ['academic_year_id', $academic_year->id]]);
        if ($status != 'null') {
            $fees = $fees->where('fee_type', $status);
        }
        $fees = $fees->paginate(2);
        \Session::put('amount', 0);
        $fees = FeeResource::collection($fees);
        /*$array['feelist'] = FeeResource::collection($fees);

        foreach ($fees as $fee)
        {
            $paidfees  = FeePayment::where('fee_id',$fee->id)->where('status',1);

            $unpaidfees  = FeePayment::where('fee_id',$fee->id)->where('status',0);

            if($fee->standardLink_id != null)
            {
                $paidfees = $paidfees->whereHas('user',function($query) use($fee)
                {
                    $query->whereHas('studentAcademicLatest',function($q) use($fee)
                    {
                        $q->where('standardLink_id',$fee->standardLink_id);
                    });
                });

                $unpaidfees = $unpaidfees->whereHas('user',function($query) use($fee)
                {
                    $query->whereHas('studentAcademicLatest',function($q) use($fee)
                    {
                        $q->where('standardLink_id',$fee->standardLink_id);
                    });
                });
            }

            $paid[$fee->id] = $paidfees->pluck('user_id')->toArray();

            $unpaid[$fee->id] = $unpaidfees->pluck('user_id')->toArray();

            $students[$fee->id] = User::whereIn('id',array_diff($unpaid[$fee->id],$paid[$fee->id]));

            $array['unpaidCount'][$fee->id] = $students[$fee->id]->count();
            //$array['unpaidStudents'][$fee->id] = UserResource::collection($students[$fee->id]->get());

            $array['paidCount'][$fee->id] = $paidfees->count();
            //$array['paidStudents'][$fee->id] = FeePaymentResource::collection($paidfees->get());
        }
        return $array;*/

        return $fees;
    }

    /**
     * Show the unpaid fees view for the provided fee id.
     *
     * @param  int  $fee_id
     * @return Response
     */
    public function show($fee_id)
    {
        return view('/admin/dashboard/unpaidfees', ['fee_id' => $fee_id]);
    }

    /**
     * Return unpaid fees list and count for a specific fee.
     *
     * @param  int  $fee_id
     * @return array
     */
    public function feeslist($fee_id)
    {
        $fees = Fee::where('id', $fee_id)->first();

        $unpaidfees = FeePayment::where('fee_id', $fees->id)->where('status', 0);

        if ($fees->standardLink_id != null) {
            $unpaidfees = $unpaidfees->whereHas('user', function ($query) use ($fees) {
                $query->whereHas('studentAcademicLatest', function ($q) use ($fees) {
                    $q->where('standardLink_id', $fees->standardLink_id);
                });
            });
        }

        $unpaidfees = $unpaidfees->get();
        $array['unpaidCount'] = $unpaidfees->count();
        $array['unpaidList'] = UnpaidFeesResource::collection($unpaidfees);

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function sendReminder(Request $request, $fee_id)
    {
        //
        try {
            $user = User::where('name', $request->name)->first();

            $feepayment = FeePayment::where('id', $fee_id)->first();

            foreach ($user->parents as $parent) {
                $array = [];

                $array['school_id'] = Auth::user()->school_id;
                $array['user_id'] = $parent->userParent->id;
                $array['message'] = $feepayment->fee->name.' Fee Payment Is Pending.Last Date For Payment - '.date('d-m-Y', strtotime($feepayment->fee->end_date));
                $array['type'] = 'private message';

                event(new SinglePushEvent($array));
            }
            $message = trans('messages.send_fee_reminder_msg');

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $feepayment,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_SEND_FEEPAYMENT_REMINDER,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
