<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\TeacherAvatarAddRequest;
use App\Services\UserProfileReaderService;
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

    public function __construct(
        protected UserProfileReaderService $userProfileReader,
        protected UserProfileWriterService $userProfileWriter
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function ChangePassword()
    {
        return view('/teacher/changepassword');
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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getavatar()
    {
        return $this->userProfileReader->avatar(Auth::id());
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function changeavatar(Request $request)
    {
        return view('/teacher/changeavatar');
    }

    /**
     * Updates the avatar image for specified user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updatechangeavatar(TeacherAvatarAddRequest $request)
    {
        return $this->userProfileWriter->updateAvatarFromBase64(Auth::id(), $request->avatar);
    }
}
