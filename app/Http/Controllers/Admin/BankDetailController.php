<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankDetailRequest;
use App\Models\TransactionAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class BankDetailController
 *
 * Controller for managing bank details for users (create, edit,
 * update and retrieve transaction account information).
 */
class BankDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($name)
    {
        $user = User::where('name', $name)->first();
        $account = TransactionAccount::where('user_id', $user->id)->first();

        // $documents = UserDocumentResource::collection($documents);
        if (! $account) {
            $account = [];
        }

        return $account;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(BankDetailRequest $request, $name)
    {
        $user = User::where('name', $name)->first();

        $account = new TransactionAccount;

        $account->school_id = $user->school_id;
        $account->user_id = $user->id;
        $account->name = $request->bank_name;
        $account->key = $request->key;
        $account->account_number = $request->account_number;
        $account->ifsc_code = $request->ifsc_code;

        $account->save();

        $message = 'Bank Details Added';

        $res['success'] = $message;

        return $res;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $account = TransactionAccount::find($id);

        return $account;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(BankDetailRequest $request, $id)
    {
        //
        $account = TransactionAccount::find($id);

        $account->name = $request->bank_name;
        $account->key = $request->key;
        $account->account_number = $request->account_number;
        $account->ifsc_code = $request->ifsc_code;

        $account->save();

        $message = 'Bank Details Updated';

        $res['success'] = $message;

        return $res;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
