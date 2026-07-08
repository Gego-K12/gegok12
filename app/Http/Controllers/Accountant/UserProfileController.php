<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\TeacherAvatarAddRequest;
use App\Services\UserProfileReaderService;
use App\Services\UserProfileWriterService;
use App\Traits\Common;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class UserProfileController
 *
 * Manages accountant user profile operations.
 *
 * Responsibilities:
 * - Change user password
 * - Update profile avatar
 * - Fetch avatar details
 * - Log profile-related activities
 */
class UserProfileController extends Controller
{
    use Common;
    use LogActivity;

    public function __construct(
        protected UserProfileReaderService $userProfileReader,
        protected UserProfileWriterService $userProfileWriter
    ) {}

    /**
     * Display the change password view.
     *
     * @return View
     */
    public function ChangePassword()
    {
        return view('/accountant/changepassword');
    }

    /**
     * Update the password of the authenticated user.
     *
     * @return array<string, string>|null
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        return $this->userProfileWriter->changePassword(Auth::id(), $request->newpassword);
    }

    /**
     * Get the authenticated user's avatar details.
     *
     * @return array<string, mixed>
     */
    public function getavatar()
    {
        return $this->userProfileReader->avatar(Auth::id());
    }

    /**
     * Display the change avatar view.
     *
     * @return View
     */
    public function changeavatar(Request $request)
    {
        return view('/accountant/changeavatar');
    }

    /**
     * Update the avatar image of the authenticated user.
     *
     * Handles base64 image decoding and storage.
     *
     * @return array<string, string>|null
     */
    public function updatechangeavatar(TeacherAvatarAddRequest $request)
    {
        return $this->userProfileWriter->updateAvatarFromBase64(Auth::id(), $request->avatar);
    }
}
