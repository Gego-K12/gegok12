<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Librarian;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Users\TeacherUser;
use App\Traits\MemberProcess;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\User;
use App\Models\AcademicYear;
use App\Helpers\SiteHelper;
use Exception;
use PDF;



class TeacherListController extends Controller
{
    use MemberProcess;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
      //
      return $this->LibraryTeacherFilter($request,Auth::user()->school_id,5);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //
      
      $count    = TeacherUser::ByRole(5)->where('school_id',Auth::user()->school_id)->count();
      $alphabet = request('alphabet')?request('alphabet'):'';
      $query    = \Request::getQueryString();
      if(request('date_of_birth') != null)
        {
            $birthday = 'true';
        }

      return view('/library/index-teacher',['alphabet'=>$alphabet,'query'=>$query,'birthday' => $birthday,'count'=>$count]);
    }

    public function destroy($name)
    {
        try
        {
            $user = User::where('name',$name)->first();
            $user->delete();

            $message=trans('messages.delete_success_msg',['module' => 'Teacher']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_TEACHER,
                $message
            ); 
            \Session::put('successmessage',$message);
            return redirect('/admin/teachers');
        }
        catch(Exception $e)
        {
        } 
    }

    public function idcard()
    {
   

       $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
      $teachers    = TeacherUser::ByRole(5)->where('school_id',Auth::user()->school_id)->get();
        // $teachers=SiteHelper::getteachers(Auth::user()->school_id,$academic->id,$standardLink->id);
//          return view('admin.id-card.id-card-new',compact('standardLink','students','academic'));

        return view('/admin/teacher/idcard', compact('teachers','academic'));
    }

    public function printidcard()
    {
       $academic = SiteHelper::getAcademicYear(Auth::user()->school_id);
      $teachers    = TeacherUser::ByRole(5)->where('school_id',Auth::user()->school_id)->get();
        $pdf = PDF::loadView('admin/teacher/idcard-print', compact('teachers','academic'));
         return $pdf->stream('result.pdf', array('Attachment'=>0)); 
    }

}