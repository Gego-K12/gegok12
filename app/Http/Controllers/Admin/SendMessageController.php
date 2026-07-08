<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Events\SendMessageEvent;
use App\Events\SendMessageTeacherEvent;
use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendMailRequest;
use App\Models\AcademicYear;
use App\Models\SendMail;
use App\Models\StudentAcademic;
use App\Models\User;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\RegisterUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class SendMessageController
 *
 * Handles sending messages to users (students, parents,
 * teachers, alumni) and manages student academic shifting.
 */
class SendMessageController extends Controller
{
    use Common;
    use LogActivity;
    use RegisterUser;

    /**
     * Display sent messages list.
     *
     * Retrieves sent messages for the current academic year
     * and applies optional user type filtering.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
        $school_id = Auth::user()->school_id;
        $academic_year = SiteHelper::getAcademicYear($school_id);
        $messages = SendMail::with('user')
            ->where([
                ['school_id', $school_id],
                ['academic_year_id', $academic_year->id],
            ])
            ->orderBy('fired_at', 'desc');

        if ($request->type != '') {
            $messages = $messages->whereHas('user', function ($query) use ($request) {
                if ($request->type == 'teacher') {
                    $query->where('usergroup_id', 5);
                } elseif ($request->type == 'parent') {
                    $query->where('usergroup_id', 7);
                } elseif ($request->type == 'alumni') {
                    $query->where('usergroup_id', 9);
                }
            });
        }

        $messages = $messages->paginate(10);

        return view('admin/member/sentmessagetoall', ['messages' => $messages]);
    }

    /**
     * Send message to all selected users.
     *
     * Dispatches SendMessageEvent for asynchronous
     * message processing.
     *
     * @return array
     */
    public function store(SendMailRequest $request)
    {
        //
        try {
            event(new SendMessageEvent(
                $request,
                Auth::user()->school_id,
                Auth::user()->email,
                Auth::user()
            ));

            $res['message'] = trans('messages.message_success_msg');

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send message specifically to teachers.
     *
     * Prepares teacher-specific message payload
     * and dispatches SendMessageTeacherEvent.
     *
     * @return array
     */
    public function storeTeacher(SendMailRequest $request)
    {
        //
        try {
            $data = [];
            $data['selected'] = $request->selected;
            $data['subject'] = $request->subject;
            $data['message'] = $request->message;
            $data['send_later'] = $request->send_later;
            $data['executed_at'] = $request->executed_at;
            $datas = (object) $data;

            event(new SendMessageTeacherEvent(
                $datas,
                Auth::user()->school_id,
                Auth::user()->email,
                Auth::user()
            ));

            $res['message'] = trans('messages.message_success_msg');

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Shift selected students to another standard
     * or convert them to alumni if applicable.
     *
     * Handles student academic transitions based on
     * selected users and target standard.
     *
     * @return array
     */
    public function shift(Request $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear($school_id);

            foreach ($request->selectedUsers as $key => $value) {
                $studentAcademic = StudentAcademic::where([
                    ['user_id', $value],
                    ['school_id', $school_id],
                    ['academic_year_id', $academic_year->id],
                ])->latest()->first();

                if ($studentAcademic) {
                    if ($studentAcademic->standardLink_id != 42) {
                        $studentAcademic->standardLink_id = $request->shift_std;
                        $studentAcademic->update();
                    } else {
                        $user = User::where('id', $value)->first();
                        $user->usergroup_id = '9';
                        $user->save();

                        $userprofile = Userprofile::where('user_id', $value)->first();
                        $userprofile->usergroup_id = '9';
                        $userprofile->save();

                        $academic_year_data = AcademicYear::where('id', $academic_year->id)->first();
                        $passing_session_year = explode('-', $academic_year_data->name);

                        $data = $user;
                        $data['name'] = $user->userprofile->firstname;
                        $data['passing_session'] = $passing_session_year['1'];
                        $data['current_studying'] = $academic_year->id;

                        $path = '';
                        $usergroup_id = '9';

                        $alumniprofile = $this->CreateAlumni($data, $path, $usergroup_id, $school_id, $user);
                    }
                }
            }

            $res['message'] = count($request->selectedUsers).' Students shifted sucessfully';

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
