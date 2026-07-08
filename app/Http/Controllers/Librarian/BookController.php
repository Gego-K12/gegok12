<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Librarian;

use App\Helpers\SiteHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\API\Book as BookResource;
use App\Http\Resources\API\BookCategory as BookCategoryResource;
use App\Models\Book;
use App\Models\BookCategory;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Log;

class BookController extends Controller
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
        $books = Book::with('category')->where('school_id', Auth::user()->school_id);

        $q = request('q');

        if ($q != '') {
            $books = $books->where(function ($query) use ($q) {
                $query->where('title', 'LIKE', $q.'%')->orWhere('author', 'LIKE', '%'.$q.'%');
            });
        }

        $books = $books->paginate(10);
        $books = $books->appends(['q' => request('q')]);

        return view('/library/books/index', ['book' => $books]);
    }

    public function list()
    {
        $category = BookCategory::where('school_id', Auth::user()->school_id)->get();
        $categorylist = BookCategoryResource::collection($category);

        return $categorylist;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('/library/books/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(BookRequest $request)
    {
        try {
            $school_id = Auth::user()->school_id;

            $academic_year = SiteHelper::getAcademicYear($school_id);

            $book = new Book;

            $book->school_id = $school_id;
            $book->academic_year_id = $academic_year->id;
            $book->author = $request->author;
            $book->title = $request->title;
            $book->category_id = $request->category_id;
            $book->book_code = $request->book_code;
            $book->isbn_number = $request->isbn_number;
            $book->availability = $request->availability;
            $cover_image = $request->file('cover_image');
            if ($cover_image) {
                $path = $this->uploadFile(Auth::user()->school->slug.'/uploads/library/books', $cover_image);
                $book->cover_image = $path;
            }

            $book->save();

            $message = trans('messages.add_success_msg', ['module' => 'Book']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $book,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_BOOK,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $book = Book::where('id', $id)->get();

        $book = BookResource::collection($book);

        return $book;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $book = Book::where([['id', $id], ['school_id', Auth::user()->school_id]])->first();

        if (Gate::allows('book', $book)) {
            return view('/library/books/edit', ['book' => $book]);
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $book = Book::where('id', $id)->where('school_id', Auth::user()->school_id)->first();

            $book->author = $request->author;
            $book->title = $request->title;
            $book->category_id = $request->category_id;
            $book->book_code = $request->book_code;
            $book->isbn_number = $request->isbn_number;
            $book->availability = $request->availability;
            $cover_image = $request->file('cover_image');
            if ($cover_image) {
                $path = $this->uploadFile(Auth::user()->school_id.'/uploads/library/books', $cover_image);
                $book->cover_image = $path;
            } else {
                $book->cover_image = $book->cover_image;
            }

            $book->save();

            $message = trans('messages.update_success_msg', ['module' => 'Book']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $book,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_BOOK,
                $message
            );

            $res['success'] = $message;

            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $book = Book::where('id', $id)->first();

            if (Gate::allows('book', $book)) {
                $book->delete();

                $message = trans('messages.delete_success_msg', ['module' => 'Book']);

                $ip = $this->getRequestIP();
                $this->doActivityLog(
                    $book,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_DELETE_BOOK,
                    $message
                );

                return redirect()->back()->with('successmessage', $message);
            } else {
                abort(403);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
