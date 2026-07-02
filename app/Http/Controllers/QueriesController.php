<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers;


use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\ContactMail;
use App\Models\Query;
use App\Models\User;
use App\Traits\MSG91;

class QueriesController extends Controller
{
    use MSG91;
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        try
        {
            $contact = new Query;

            $contact->name          = $request->fullname;
            $contact->email         = $request->emailid;
            $contact->school_name   = $request->serve_at;
            $contact->designation   = $request->role;
            $contact->phone         = $request->contact_no;
            $contact->message       = $request->message;
            $contact->channel       = $request->select;

            $contact->save();

            $message= "Contact Form Submitted";

            $res['success'] = $message;

            return $res;
        }
        catch(Exception $e)
        {
        }
    }

  /*  public function checksms()
    {
        $this->sendSMS($content,'919042781117');
    }*/
}
