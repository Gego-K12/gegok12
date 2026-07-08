<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Common;
use App\Traits\LogActivity;
use App\Traits\MemberProcess;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PDF;

class TeacherListController extends Controller
{
    use Common;
    use LogActivity;
    use MemberProcess;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function find(Request $request)
    {
        //
        return $this->TeacherFilter($request, Auth::user()->school_id, 5);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $count = User::ByRole(5)->where('school_id', Auth::user()->school_id)->count();
        $alphabet = request('alphabet') ? request('alphabet') : '';
        $query = \Request::getQueryString();
        if (request('date_of_birth') != null) {
            $birthday = 'true';
        }

        return view('/admin/teacher/index', ['alphabet' => $alphabet, 'query' => $query, 'birthday' => $birthday, 'count' => $count]);
    }

    public function destroy($name)
    {
        try {
            $user = User::where('name', $name)->first();
            $user->delete();

            $message = trans('messages.delete_success_msg', ['module' => 'Teacher']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_TEACHER,
                $message
            );
            \Session::put('successmessage', $message);

            return redirect('/admin/teachers');
        } catch (Exception $e) {
        }
    }

    public function idcard()
    {

        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teachers = User::ByRole(5)->where('school_id', Auth::user()->school_id)->get();

        return view('/admin/teacher/idcard', compact('teachers', 'academic'));
    }

    public function printidcard()
    {
        $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
        $teachers = User::ByRole(5)->where('school_id', Auth::user()->school_id)->get();
        $pdf = PDF::loadView('admin/teacher/idcard-print', compact('teachers', 'academic'));

        return $pdf->stream('result.pdf', ['Attachment' => 0]);
    }
}
