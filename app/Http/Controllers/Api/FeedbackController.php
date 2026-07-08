<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Events\Notification\SingleNotificationEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use App\Http\Resources\API\Feedback as FeedbackResource;
use App\Http\Resources\API\Notification as NotificationResource;
use App\Http\Resources\API\SendMail as SendMailResource;
use App\Models\Feedback;
use App\Models\FeedbackMessage;
use App\Models\SendMail;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

class FeedbackController extends Controller
{
    use Common;
    use LogActivity;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $parent = User::where('id', Auth::id())->first();
        $feedback = Feedback::where('parent_id', $parent->id)->get();

        $feedback = FeedbackResource::collection($feedback);

        return response()->json([
            'success' => true,
            'message' => 'Feedbacks List',
            'data' => $feedback,
        ], 200);
    }

    public function list()
    {
        // $categoryList = SiteHelper::getFeedbackCategoryList();

        // $type=array(
        //     [
        //     'id' =>'feedback_or_bug_for_app_or_software',
        //     'name' =>'Feedback / Bug For App / Software'
        //      ]
        //      ,[
        //         'id' =>'student_profile_or_info',
        //         'name' =>'Student Profile / Info'
        //     ],
        //     [
        //         'id' =>'complaints',
        //         'name' =>'Complaints'
        //     ],
        //     [
        //         'id' =>'suggestions',
        //         'name' =>'Suggestions'
        //     ],[
        //         'id' =>'others',
        //         'name' =>'Others'
        //     ]
        //       );

        $type = [
            [
                'id' => 'complaints',
                'name' => 'Complaints',
            ],
            [
                'id' => 'suggestions',
                'name' => 'Suggestions',
            ], [
                'id' => 'others',
                'name' => 'Others',
            ],
        ];

        $categoryList = $type;

        return response()->json([
            'success' => true,
            'message' => 'Feedback Category List',
            'data' => $categoryList,
        ], 200);
    }

    /*public function conversationsave(FeedbackRequest $request,$feedbackid)
    {
        //
        try
        {
            $feedbackMessage = new FeedbackMessage;

            $feedbackMessage->message       = $request->message;
            $feedbackMessage->user_id       = Auth::id();
            $feedbackMessage->school_id     = Auth::user()->school_id;
            $feedbackMessage->feedback_id   = $feedbackid;

            $feedbackMessage->save();

            $res['message'] = 'Message Sent Successfully';
            return $res;
        }
        catch(Exception $e)
        {
        }
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(FeedbackRequest $request, $student_id)
    {
        //
        try {
            $parent = User::where('id', Auth::id())->first();
            $admin = User::where('school_id', $parent->school_id)->ByRole(3)->first();

            $feedback = new Feedback;

            $feedback->school_id = Auth::user()->school_id;
            $feedback->parent_id = $parent->id;
            $feedback->student_id = $student_id;
            $feedback->admin_id = $admin->id;

            if ($feedback->save()) {
                $feedbackMessage = new FeedbackMessage;

                $feedbackMessage->message = $request->message;
                $feedbackMessage->category = $request->category;
                $feedbackMessage->user_id = Auth::id();
                $feedbackMessage->school_id = Auth::user()->school_id;
                $feedbackMessage->feedback_id = $feedback->id;

                if ($feedbackMessage->save()) {
                    $message = trans('messages.add_success_msg', ['module' => 'Feedback']);

                    $ip = $this->getRequestIP();
                    $this->doActivityLog(
                        $feedbackMessage,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                        LOGNAME_ADD_FEEDBACK,
                        $message
                    );

                    $array = [];

                    $array['user'] = $admin;
                    $array['details'] = trans('notification.feedback_add_success_msg');

                    event(new SingleNotificationEvent($array));

                    $res['message'] = $message;
                } else {
                    $res['message'] = 'Failed To Send Message';
                }
            } else {
                $res['message'] = 'Failed To Send Message';
            }

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function sentMessages()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $messages = SendMail::where([['school_id', $school_id], ['academic_year_id', $academic_year->id], ['user_id', Auth::id()]])->whereHas('user', function ($query) {
            $query->where('usergroup_id', 7);
        })->orderBy('fired_at', 'desc')->get();

        $count = count($messages);

        $messages = SendMailResource::collection($messages);

        return response()->json([
            'success' => true,
            'message' => 'Messages List',
            'type' => 'message',
            'count' => $count,
            'data' => $messages,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function readMessage(Request $request, $id)
    {
        //
        try {
            $message = SendMail::where([['id', $id], ['user_id', Auth::id()]])->first();

            $message->read_status = 1;
            $message->read_at = Carbon::now();

            $message->save();

            return response()->json([
                'success' => true,
                // 'message'   =>  'Messages List',
                // 'data'      =>  $messages
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function notifications($id)
    {
        //
        $school_id = Auth::user()->school_id;

        $academic_year = SiteHelper::getAcademicYear($school_id);

        $user = User::where('school_id', $school_id)->where('id', $id)->first();

        if ($user) {

            $notifications = \DB::table('notifications')->where('notifiable_id', $id)->latest()->get();

            $notifications = NotificationResource::collection($notifications);

            return response()->json([
                'success' => true,
                'message' => 'Notification List',
                'type' => 'notification',
                'data' => $notifications,
            ], 200);

        } else {
            return response()->json([
                'error' => 'unauthorised',
                // 'message'   =>  'Messages List',
                // 'data'      =>  $messages
            ], 200);
        }
    }
}
