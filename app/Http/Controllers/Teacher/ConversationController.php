<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {

        // $conversations=$request->user()->conversations()->orderBy('last_message_at','desc')->get();
        $conversations = $request->user()->conversations()->get();

        return view('teacher.conversations.index', ['conversations' => $conversations]);
    }

    public function show(Conversation $conversation, Request $request)
    {
        // $conversation = Conversation::where('id',$id)->get();

        $conversations = $request->user()->conversations();

        /*  $conversations->updateExistingPivot($conversation, [

                  'read_at' => now()
              ]);

          $conversations= $conversations->orderBy('last_message_at','desc')->get();*/
        return view('teacher.conversations.show', ['conversations' => $conversations, 'conversation' => $conversation]);
    }

    public function create(Request $request)
    {
        // $conversations=$request->user()->conversations()->orderBy('last_message_at','desc')->get();
        $conversations = $request->user()->conversations()->get();

        return view('teacher.conversations.create', ['conversations' => $conversations]);
    }
}
