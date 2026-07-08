<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ExportMemberController
 *
 * Handles student/member export operations such as
 * CSV export (default & custom fields) and PDF export.
 */
class ExportMemberController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;

    /**
     * Display the export page.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin/member/export/export');
    }

    /**
     * Export students with default columns as CSV.
     *
     * @return void
     */
    public function exportUsers(Request $request)
    {
        $users = $this->MemberFilter($request, Auth::user()->school_id, 6, 'active');
        $csv = Writer::createFromFileObject(new \SplTempFileObject);

        if (count($users) > 0) {
            $csv->insertOne(['firstname', 'lastname', 'parent_name', 'standard', 'admission_number', 'EMIS', 'Joining_date', 'caste', 'adhaar', 'blood_group', 'gender', 'date_of_birth', 'address', 'city', 'state', 'country', 'pincode', 'mobile_no', 'email', 'notes', 'status']);

            foreach ($users as $user) {
                $csv->insertOne([
                    $user->userprofile->firstname,
                    $user->userprofile->lastname,
                    $user->members[0]['userprofile']['firstname'].' '.$user->members[0]['userprofile']['lastname'],
                    $user->studentAcademicLatest->standardLink->StandardSection,
                    $user->userprofile->registration_number,
                    $user->userprofile->EMIS_number,
                    $user->userprofile->joining_date,
                    $user->userprofile->caste,
                    $user->userprofile->aadhar_number,
                    $user->userprofile->blood_group,
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

    /**
     * Store selected headings for CSV export in session.
     *
     * @return void
     */
    public function studentexport(Request $request)
    {
        \Session::forget('headings');
        $heads = [];
        $heads = array_values($request->headings);
        \Session::put('headings', $heads);
    }

    /**
     * Export students as CSV with selected headings.
     *
     * @return void
     */
    public function studentexports(Request $request)
    {
        $headings = \Session::get('headings');
        $heads = array_values($headings);

        $users = $this->MemberFilter($request, Auth::user()->school_id, 6, 'active');
        $csv = Writer::createFromFileObject(new \SplTempFileObject);

        $default = ['name', 'email', 'mobile_no', 'parent_name', 'standard', 'gender', 'admission_number', 'EMIS', 'Joining_date', 'caste', 'adhaar', 'blood_group', 'date_of_birth', 'address', 'city', 'state', 'country', 'pincode', 'notes'];
        $result = [];
        $result = array_intersect($default, $heads);
        $result = array_map('ucfirst', $result);

        if (count($users) > 0) {
            $csv->insertOne($result);

            foreach ($users as $user) {
                $data = [];
                if (in_array('name', $heads)) {
                    $data[] = $user->FullName;
                }
                if (in_array('email', $heads)) {
                    $data[] = $user->email;
                }
                if (in_array('mobile_no', $heads)) {
                    if (count($user->parents) > 0) {
                        $data[] = $user->parents[0]['userParent']['mobile_no'];
                    } else {
                        $data[] = $user->mobile_no;
                    }
                }
                if (in_array('parent_name', $heads)) {
                    if (count($user->parents) > 0) {
                        $data[] = $user->parents[0]['userParent']['userprofile']['firstname'].' '.$user->parents[0]['userParent']['userprofile']['lastname'].'('.ucfirst($user->parents[0]['userParent']->getParentDetails()['relation']).')';
                    } else {
                        $data[] = '';
                    }
                }
                if (in_array('standard', $heads)) {
                    $data[] = $user->studentAcademicLatest->standardLink->StandardSection;
                }
                if (in_array('gender', $heads)) {
                    $data[] = $user->userprofile->gender;
                }
                if (in_array('admission_number', $heads)) {
                    $data[] = $user->userprofile->registration_number;
                }
                if (in_array('EMIS', $heads)) {
                    $data[] = $user->userprofile->EMIS_number;
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

    /**
     * Store selected headings for PDF export.
     *
     * @return void
     */
    public function exportpdf(Request $request)
    {
        \Session::forget('headings');
        $heads = [];
        $heads = array_values($request->headings);
        \Session::put('headings', $heads);
    }

    /**
     * Export students as PDF.
     *
     * @return BinaryFileResponse
     */
    public function exportpdfs(Request $request)
    {
        $array = [];
        $users = $this->MemberFilter($request, Auth::user()->school_id, 6, 'active');
        $array['users'] = $users;

        $pdf = PDF::loadView('/admin/export/student', $array);

        $folder = Auth::user()->school->slug.'/export';
        $filename = 'ExportStudents-'.date('d-m-Y').'.pdf';

        $file = $this->putContents($folder.'/'.$filename, $pdf->output());

        return $pdf->download($filename);
    }
}
