<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Bulletin as BulletinResource;
use App\Models\Bulletin;
use Illuminate\Support\Facades\Auth;

class BulletinsController extends Controller
{
    public function show()
    {
        $bulletins = Bulletin::where('school_id', Auth::user()->school_id)->paginate(5);
        $bulletins = BulletinResource::collection($bulletins);

        return $bulletins;
    }
}
