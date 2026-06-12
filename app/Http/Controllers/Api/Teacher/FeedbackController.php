<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Api\Teacher;

use App\Http\Resources\API\Teacher\Notification as NotificationResource;
use App\Http\Resources\API\Teacher\Feedback as FeedbackResource;
use App\Http\Resources\API\Teacher\SendMail as SendMailResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\FeedbackMessage;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\SendMail;
use App\Models\Feedback;
use App\Traits\Common;
use App\Models\User;
use Exception;

class FeedbackController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $parent = User::where('id',Auth::id())->first();
        $feedback = Feedback::where('parent_id',$parent->id)->get();

        $feedback = FeedbackResource::collection($feedback);
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Feedbacks List',
            'data'      =>  $feedback
        ],200);
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
            //dd($e->getMessage());
        }
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedbackRequest $request,$student_id)
    {
        //
        try
        {
            $parent = User::where('id',Auth::id())->first();
            $admin = User::where('school_id',$parent->school_id)->ByRole(3)->first();
            
            $feedback = new Feedback;

            $feedback->school_id    = Auth::user()->school_id;
            $feedback->parent_id    = $parent->id;
            $feedback->student_id   = $student_id;
            $feedback->admin_id     = $admin->id;

            if($feedback->save())
            {
                $feedbackMessage = new FeedbackMessage;

                $feedbackMessage->message       = $request->message;
                $feedbackMessage->user_id       = Auth::id();
                $feedbackMessage->school_id     = Auth::user()->school_id;
                $feedbackMessage->feedback_id   = $feedback->id;

                if($feedbackMessage->save())
                {
                    $message = trans('messages.add_success_msg',['module' => 'Feedback']);

                    $ip= $this->getRequestIP();
                    $this->doActivityLog(
                        $feedbackMessage,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                        LOGNAME_ADD_FEEDBACK,
                        $message
                    );

                    $array = [];

                    $array['user']      = $admin;
                    $array['details']   = trans('notification.feedback_add_success_msg');  

                    event(new SingleNotificationEvent($array));

                    $res['message'] = $message;
                }
                else
                {
                    $res['message'] = 'Failed To Send Message';
                }
            }
            else
            {
                $res['message'] = 'Failed To Send Message';
            }

            return $res;
        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sentMessages()
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $messages =  SendMail::where([['school_id',$school_id],['academic_year_id',$academic_year->id],['user_id',Auth::id()]])->whereHas('user',function ($query) 
            {
                $query->where('usergroup_id',5);
            })->orderBy('fired_at','desc')->paginate(10);

        

        $count=count($messages);

        $messages = SendMailResource::collection($messages);
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  'Messages List',
            'type'      =>  'message',
            'count'      =>  $count,
            'data'      =>  $messages,
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
                'next_page_url' => $messages->nextPageUrl(),
                'prev_page_url' => $messages->previousPageUrl(),
            ]
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function readMessage(Request $request,$id)
    {
        //
        try
        {
            $message =  SendMail::where([['id',$id],['user_id',Auth::id()]])->first();

            $message->read_status = 1;
            $message->read_at = Carbon::now();

            $message->save();

            return response()->json([
                'success'   =>  true,
                //'message'   =>  'Messages List',
                //'data'      =>  $messages
            ],200);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    public function notifications($id)
    {
        //
        $school_id = Auth::user()->school_id;

        $academic_year = SiteHelper::getAcademicYear($school_id);

        $user=User::where('school_id',$school_id)->where('id',$id)->first();

        if($user)
        {

            $notifications=\DB::table('notifications')->where('notifiable_id',$id)->latest()->get();
            // ->paginate(10);

            $notifications = NotificationResource::collection($notifications);
            
            return response()->json([
                'success'   =>  true,
                'message'   =>  'Notification List',
                'type'      =>  'notification',
                'data'      =>  $notifications,
                // 'meta' => [
                //     'current_page' => $notifications->currentPage(),
                //     'last_page' => $notifications->lastPage(),
                //     'per_page' => $notifications->perPage(),
                //     'total' => $notifications->total(),
                //     'next_page_url' => $notifications->nextPageUrl(),
                //     'prev_page_url' => $notifications->previousPageUrl(),
                // ]
            ],200);

        }

        else
        {
              return response()->json([
                'error'   =>  'unauthorised',
                //'message'   =>  'Messages List',
                //'data'      =>  $messages
            ],200);
        }
    }
}
