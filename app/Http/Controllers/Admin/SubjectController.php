<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectAddRequest;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use App\Models\Teacherlink;
use App\Traits\AcademicProcess;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    use AcademicProcess;
    use Common;
    use LogActivity;

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(SubjectRequest $request)
    {
        //
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $subject = $this->createSubject($school_id, $academic_year->id, $request);

            $message = trans('messages.add_success_msg', ['module' => 'Subject']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $subject,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_SUBJECT,
                $message
            );

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Subject']);

            return $res;
        } catch (Exception $e) {
        }
    }

    public function create(SubjectAddRequest $request)
    {
        //
        // \DB::beginTransaction();
        try {
            $school_id = Auth::user()->school_id;
            $academic_year = SiteHelper::getAcademicYear(Auth::user()->school_id);

            $subject = $this->addSubject($school_id, $academic_year->id, $request);

            $message = trans('messages.add_success_msg', ['module' => 'Subject']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $subject,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_SUBJECT,
                $message
            );

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Subject']);

            return $res;
            // \DB::commit();
        } catch (Exception $e) {
            // \DB::rollBack();
        }
    }

    public function addSubject($school_id, $academic_year_id, $data)
    {
        try {
            for ($i = 0; $i < $data->subjectscount; $i++) {
                $subject_name = 'subject_name'.$i;
                $subject_code = 'subject_code'.$i;
                $subject_type = 'subject_type'.$i;
                $subject = new Subject;

                $subject->school_id = $school_id;
                $subject->academic_year_id = $academic_year_id;
                $subject->standard_id = $data->subject_standard_id;
                $subject->section_id = $data->subject_section_id;
                $subject->name = ucfirst($data->$subject_name);
                $subject->code = $data->$subject_code;
                $subject->type = $data->$subject_type;
                $subject->status = 1;

                $subject->save();
            }

            return $subject;
        } catch (Exception $e) {
        }
    }

    public function destroy($id)
    {
        try {

            Subject::where('id', $id)->delete();

            Teacherlink::where('subject_id', $id)->delete();

            $res['success'] = 'Subject has been deleted successfully';

            return $res;

        } catch (Exception $e) {
        }

    }
}
