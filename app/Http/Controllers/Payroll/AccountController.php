<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\TransactionAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $account = TransactionAccount::get();

        return view('accountant/payroll/account/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('accountant/payroll/account/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $account = new TransactionAccount;
        $account->name = $request->name;
        $account->key = $request->key;
        $account->save();

        return view('accountant/payroll/account/index')->with('success', 'Account created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('accountant/payroll/account/edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $account = TransactionAccount::find($id);
        $account->name = $request->name;
        $account->key = $request->key;
        $account->update();

        return view('accountant/payroll/account/index')->with('success', 'Account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $account = TransactionAccount::find($id);
        $account->delete();

        return back()->with('success', 'Account deleted successfully');
    }
}
