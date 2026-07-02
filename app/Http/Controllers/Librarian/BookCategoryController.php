<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers\Librarian;

use App\Http\Resources\API\BookCategory as BookCategoryResource;
use App\Http\Requests\BookCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\BookCategory;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Book;
use Exception;
use Log;

class BookCategoryController extends Controller
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
        $category = BookCategory::where('school_id',Auth::user()->school_id);

        $q = request('q');

        if($q!= '')
        {
            $category= $category->where(function ($query) use($q)
            {
                $query->where('category','LIKE','%'.$q.'%'); 
            });
        }

        $category=$category->paginate(10);
        $category=$category->appends(array('q' =>request('q')));

        return view('/library/bookscategory/index',['category'=>$category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/library/bookscategory/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookCategoryRequest $request)
    {
        try
        {
            $category = new BookCategory;

            $category->school_id = Auth::user()->school_id;
            $category->category  = $request->category;

            $category->save();

            $message = trans('messages.add_success_msg',['module' => 'Book Category']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_BOOK_CATEGORY,
                $message
            ); 

            return redirect()->back()->with('successmessage',$message);
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
        $category=BookCategory::where('id',$id)->get();
        
        $category = BookCategoryResource::collection($category);

        return $category;
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = BookCategory::where([['id',$id],['school_id',Auth::user()->school_id]])->first();

        if(Gate::allows('category',$category))
        {
            return view('/library/bookscategory/edit' , ['category' => $category]);
        }
        else
        {
            abort(403);
        }
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
            $category = BookCategory::where('id',$id)->where('school_id',Auth::user()->school_id)->first();

            $category->category = $request->category;
           
            $category->save();

            $message = trans('messages.update_success_msg',['module' => 'Book Category']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_BOOK_CATEGORY,
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
            $category = BookCategory::where('id',$id)->first();

            if(Gate::allows('category',$category))
            {
                $books = Book::where('category_id',$id)->where('school_id',Auth::user()->school_id)->get();
                foreach ($books as $book) 
                {
                    $book->delete();
                }

                $category->delete();
                
                $message=trans('messages.delete_success_msg',['module' => 'Book Category']);

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $category,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_BOOK_CATEGORY,
                    $message
                );

                return redirect()->back()->with('successmessage',$message);
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
        }
    }
}