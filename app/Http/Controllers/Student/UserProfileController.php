<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\UserProfileWriterService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    use Common;
    use LogActivity;

    public function __construct(protected UserProfileWriterService $userProfileWriter) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function ChangePassword()
    {
        return view('/student/changepassword');
    }

    /**
     * Updates the password of the specified user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        return $this->userProfileWriter->changePassword(Auth::id(), $request->newpassword);
    }
}
