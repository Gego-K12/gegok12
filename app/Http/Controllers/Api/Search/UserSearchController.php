<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSearchController extends Controller
{
    public function index(Request $request)
    {
        if (! $q = request('q', '')) {
            return response()->json([]);
        }

        return User::where('name', 'LIKE', '%'.Str::lower($q).'%')->get(['id', 'name']);
        // return User::where(DB::raw('Lower(name)'), 'LIKE', '%' .Str::lower($q). '%')->get(['id','name']);
    }
}
