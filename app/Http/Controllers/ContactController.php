<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use App\Models\Contact;
use App\Models\User;
use App\Traits\MSG91;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    use MSG91;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /* $contacts = Contact::paginate(10);

         return view('admin.contacts.show',[
                         'contacts'=>$contacts,

             ]);*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('welcome.contact');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(ContactRequest $request)
    {

        $contact = new Contact;
        $contact->fullname = $request->fullname;
        $contact->email = $request->emailid;
        $contact->serve_at = $request->serve_at;
        $contact->role = $request->role;
        $contact->contact_no = $request->contact_no;
        //  $contact->message = $request->message;
        $contact->select = $request->select;
        $contact->save();

        $user = User::find(1);

        if (env('MAIL_STATUS') == 'on') {
            Mail::to($user->email)->send(new ContactMail($contact));
        }
        $message = __('notes.notes_message');

        $res['message'] = __('notes.notes_message');

        return $res;

    }

    /*  public function checksms()
      {
          $this->sendSMS($content,'919042781117');
      }*/
}
