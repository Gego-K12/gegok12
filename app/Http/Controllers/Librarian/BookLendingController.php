<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Librarian;

use App\Http\Resources\API\BookLending as BookLendingResource;
use App\Events\Notification\SingleNotificationEvent;
use App\Http\Requests\BookLendingRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\LibraryCard;
use App\Models\BookLending;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

class BookLendingController extends Controller
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
        $booklending = BookLending::where('status','pending');

        $q = request('q');

        if ($q != '') {
            $booklending = $booklending->where(function ($query) use ($q) {
                $query->where('book_code_no', 'LIKE', '%' . $q . '%')
                  ->orWhere('library_card_no', 'LIKE', '%' . $q . '%');
            });
        }

        $booklending = $booklending->paginate(10);
        $booklending = $booklending->appends(array('q' =>request('q')));

        $currentDate = date('Y-m-d');

        return view('/library/booklending/index',['booklending' => $booklending , 'currentDate' => $currentDate]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('library/booklending/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookLendingRequest $request)
    {
        try
        {
            $library_card = LibraryCard::where('library_card_no',$request->library_card_no)->first();

            $booklending = new BookLending;

            $booklending->user_id           =   $library_card->user_id;
            $booklending->library_card_no   =   $request->library_card_no;
            $booklending->book_code_no      =   $request->book_code_no;
            $booklending->issue_date        =   date('Y-m-d',strtotime($request->issue_date));
            $booklending->return_date       =   date('Y-m-d',strtotime('+5 days',strtotime($booklending->issue_date)));
            $booklending->status            =   'pending';
            $booklending->issued_by         =   Auth::id();

            $booklending->save();

            $message = trans('messages.add_success_msg',['module' => 'Book']);

            $data = [];

            $data['user']       = User::where('id',$library_card->user_id)->first();
            $data['details']    = trans('notification.book_add_success_msg',['book' => $booklending->book[0]['title'] , 'date' => date('d-m-Y',strtotime($booklending->return_date))]);

            event(new SingleNotificationEvent($data));

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $booklending,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_BOOK_LENDING,
                $message
            ); 

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booklending=BookLending::where('id',$id)->get();

        $booklending=BookLendingResource::collection($booklending);

        return $booklending;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $booklending = BookLending::where('id',$id)->first();

        return view('/library/booklending/edit' , ['booklending' => $booklending]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            $booklending = BookLending::where('id',$id)->first();

            $library_card = LibraryCard::where('library_card_no',$request->library_card_no)->first();

            $booklending->user_id           =   $library_card->user_id;
            $booklending->library_card_no   =   $request->library_card_no;
            $booklending->book_code_no      =   $request->book_code_no;
            $booklending->issue_date        =   date('Y-m-d',strtotime($request->issue_date));
            $booklending->return_date       =   date('Y-m-d',strtotime('+5 days',strtotime($booklending->issue_date)));
            $booklending->status            =   $request->status;
            $booklending->issued_by         =   Auth::id();

            $booklending->save();

            $message = trans('messages.update_success_msg',['module' => 'Book']);

            if($booklending->status == 'pending')
            {
                $details = trans('notification.book_update_success_msg',['book' => $booklending->book[0]['title'] , 'date' => date('d-m-Y',strtotime($booklending->return_date))]);
            }
            elseif($booklending->status == 'returned')
            {
                $details = trans('notification.book_return_success_msg',['book' => $booklending->book[0]['title']]);
            }
            elseif($booklending->status == 'cancel')
            {
                $details = trans('notification.book_cancel_success_msg',['book' => $booklending->book[0]['title']]);
            }

            $data = [];

            $data['user'] = User::where('id',$library_card->user_id)->first();
            $data['details'] = $details;

            event(new SingleNotificationEvent($data));

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $booklending,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_BOOKLENDING,
                $message
            ); 

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         try
        {
            $booklending = BookLending::where('id',$id)->first();
            $details = trans('notification.book_delete_success_msg',['book' => $booklending->book[0]['title']]);
            $user = User::where('id',$booklending->user_id)->first();

            $booklending->delete();

            $message=trans('messages.delete_success_msg',['module' => 'Book']);

            $data = [];

            $data['user'] = $user;
            $data['details'] = $details;

            event(new SingleNotificationEvent($data));

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $booklending,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_BOOKLENDING,
                $message
            );

            return redirect()->back()->with('successmessage',$message);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
    public function bookreturn()
    {
        $booklending = BookLending::where('status','returned');

        $q = request('q');

        if($q!= '')
        {
            $booklending = $booklending->where(function ($query) use ($q) {
                $query->where('book_code_no', 'LIKE', '%' . $q . '%')
                  ->orWhere('library_card_no', 'LIKE', '%' . $q . '%');
            });               
        }

        $booklending = $booklending->paginate(10);
        
             

        $booklending = $booklending->appends(array('q' =>request('q')));

        $currentDate = date('Y-m-d');

        return view('/library/booklending/return',['booklending' => $booklending , 'currentDate' => $currentDate , 'category' => $category]);
    }
}