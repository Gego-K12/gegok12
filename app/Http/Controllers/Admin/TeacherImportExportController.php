<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportMemberRequest;
use App\Imports\TeachersImport;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use Maatwebsite\Excel\Facades\Excel;

class TeacherImportExportController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function export(Request $request)
    {
        $users = $this->TeacherFilter($request, Auth::user()->school_id, 5);
        $csv = Writer::createFromFileObject(new \SplTempFileObject);

        if (count($users) > 0) {
            $csv->insertOne(['employee_id', 'designation', 'firstname', 'lastname', 'gender', 'date_of_birth', 'address', 'city', 'state', 'country', 'pincode', 'mobile_no', 'email', 'notes', 'status']);

            foreach ($users as $user) {
                $csv->insertOne([
                    $user->getTeacherDetails()['employee_id'],
                    $user->getTeacherDetails()['designation'] == 'others' ? $user->getTeacherDetails()['sub_designation'] : $user->getTeacherDetails()['designation'],
                    $user->userprofile->firstname,
                    $user->userprofile->lastname,
                    $user->userprofile->gender,
                    date('d-m-Y', strtotime($user->userprofile->date_of_birth)),
                    $user->userprofile->address,
                    $user->userprofile->city->name,
                    $user->userprofile->state->name,
                    $user->userprofile->country->name,
                    $user->userprofile->pincode,
                    $user->mobile_no,
                    $user->email,
                    $user->userprofile->notes,
                    $user->userprofile->status,
                ]);
            }
        } else {
            $csv->insertOne(['No Records Found']);
            $csv->output('SP Teacher Export'.date('_d-m-Y_H:i').'.csv');
        }
        $csv->output('SP Teacher Export'.date('_d-m-Y_H:i').'.csv');
        $message = trans('messages.export_success_msg', ['module' => 'Teacher']);

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_EXPORT_TEACHER,
            $message
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function importCreate()
    {
        //
        return view('admin/teacher/import');
    }

    /**
     * @return Collection
     */
    public function import(ImportMemberRequest $request)
    {
        //
        try {
            Excel::import(new TeachersImport, $request->file('import_file'));
            $count = \Session::get('count');
            if ($count != 0) {
                return back()->with('failmessage', 'You can add only '.$count.' Members');
            }

            $insertedcount = \Session::get('insertedcount');
            if ($insertedcount > 0) {
                $message = trans('messages.import_success_msg', ['module' => 'Teacher']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    Auth::user(),
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_IMPORT_TEACHER,
                    $message
                );

                return back()->with('successmessage', $insertedcount.' '.trans('messages.insert_success_msg'));
            } else {
                return back()->with('failmessage', trans('messages.insert_failure_msg'));
            }
        } catch (Exception $e) {
        }
    }

    public function downloadFormat(Request $request)
    {
        $csv = Writer::createFromFileObject(new \SplTempFileObject);

        $csv->insertOne(['firstname', 'lastname', 'mobile_no', 'email', 'gender', 'date_of_birth', 'blood_group', 'address', 'city', 'state', 'country', 'pincode', 'aadhar_number', 'joining_date', 'employee_id', 'ug_degree', 'pg_degree', 'specialization', 'additional_coures', 'sub_additional_coures', 'designation', 'notes']);
        $csv->insertOne([
            'firstname',
            'lastname',
            'mobile_no',
            'email',
            '(male , female)',
            'date_of_birth',
            '(a+,a1+,b+,b1+,o+,ab+,a1b+,a-,a1-,b-,b1-,o-,ab-,a1b-)',
            'address',
            'city',
            'state',
            'country',
            'pincode',
            'aadhar_number',
            'joining_date',
            'employee_id',
            'ug degree (full form)',
            'pg degree (full form)',
            'specialization',
            'Elementary Teacher Education Program , Bachelor of Elementary Education (B.EI.Ed.) , Physical Education Program (C.P.Ed.) , Pre-School Teacher Education Program , Nursery Teacher Education Program , Junior Basic Training (JBT) , Teachers Training Certificate (TTC) , Diploma in Education (DED) , Nursery Teacher Training (NTT) , Elementary Teacher Education (ETE) , Primary Teachers Certificate (PTC) , Basic Training Certificate (BTC) , Others',
            'if additional_coures is others',
            'assistant_teacher,co_ordinator,head_of_the_department,librarian,others,principal,senior_teacher,teacher,vice_principal',
            'notes',
        ]);

        $csv->output('School Plus Add Teacher Format'.date('_d-m-Y_H:i').'.csv');

        $message = ('Downloaded Sample Format File Successfully');

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_DOWNLOAD_SAMPLE_FORMAT_TEACHER,
            $message
        );
    }

    public function teacherexport(Request $request)
    {

        /* if(!\Session::has('headings'))
          {*/
        \Session::forget('teacher_headings');
        // }
        $heads = [];
        $heads = array_values($request->headings);
        \Session::put('teacher_headings', $heads);

    }

    public function teacherexports(Request $request)
    {
        $headings = \Session::get('teacher_headings');
        $heads = array_values($headings);
        $users = $this->TeacherFilter($request, Auth::user()->school_id, 5);
        $csv = Writer::createFromFileObject(new \SplTempFileObject);
        $default = ['employee_id', 'designation', 'name', 'email', 'mobile_no', 'gender', 'Joining_date', 'adhaar', 'blood_group', 'date_of_birth', 'address', 'city', 'state', 'country', 'pincode'];
        $result = [];
        $result = array_intersect($default, $heads);
        $result = array_map('ucfirst', $result);

        if (count($users) > 0) {
            $csv->insertOne($result);

            foreach ($users as $user) {
                $data = [];
                if (in_array('employee_id', $heads)) {
                    $data[] = $user->getTeacherDetails()['employee_id'];
                }
                if (in_array('designation', $heads)) {
                    $data[] = $user->getTeacherDetails()['designation'] == 'others' ? $user->getTeacherDetails()['sub_designation'] : $user->getTeacherDetails()['designation'];
                }
                if (in_array('name', $heads)) {
                    $data[] = $user->FullName;
                }
                if (in_array('email', $heads)) {
                    $data[] = $user->email;
                }
                if (in_array('mobile_no', $heads)) {
                    $data[] = $user->mobile_no;
                }
                if (in_array('gender', $heads)) {
                    $data[] = $user->userprofile->gender;
                }
                if (in_array('Joining_date', $heads)) {
                    $data[] = $user->userprofile->joining_date;
                }
                if (in_array('caste', $heads)) {
                    $data[] = $user->userprofile->caste;
                }
                if (in_array('adhaar', $heads)) {
                    $data[] = $user->userprofile->aadhar_number;
                }
                if (in_array('blood_group', $heads)) {
                    $data[] = $user->userprofile->blood_group;
                }
                if (in_array('date_of_birth', $heads)) {
                    $data[] = date('d-m-Y', strtotime($user->userprofile->date_of_birth));
                }
                if (in_array('address', $heads)) {
                    $data[] = $user->userprofile->address;
                }
                if (in_array('city', $heads)) {
                    $data[] = $user->userprofile->city->name;
                }
                if (in_array('state', $heads)) {
                    $data[] = $user->userprofile->state->name;
                }
                if (in_array('country', $heads)) {
                    $data[] = $user->userprofile->country->name;
                }
                if (in_array('pincode', $heads)) {
                    $data[] = $user->userprofile->pincode;
                }

                $csv->insertOne($data);
            }
        } else {
            $csv->insertOne(['No Records Found']);
            $csv->output('SP Student Export'.date('_d-m-Y_H:i').'.csv');
        }
        $csv->output('SP Student Export'.date('_d-m-Y_H:i').'.csv');
        $message = trans('messages.export_success_msg', ['module' => 'Student']);

        $ip = $this->getRequestIP();
        $this->doActivityLog(
            Auth::user(),
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            LOGNAME_EXPORT_STUDENT,
            $message
        );

    }
}
