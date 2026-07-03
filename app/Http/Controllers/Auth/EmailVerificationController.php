<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Email verification controller.
 *
 * Handles confirming a user's email address using a verification token
 * and redirects users to the appropriate route after verification.
 */
class EmailVerificationController extends Controller
{
    /**
     * Verify a user's email using the provided token.
     *
     * Searches for a user with the matching `email_verification_code` and,
     * if found, marks the user's `email_verified` flag and `email_verified_at` timestamp.
     * Redirects to `/home` if an authenticated user is present, otherwise to `login`.
     *
     * @param  string  $token  Verification token from the email
     * @return RedirectResponse|null Redirect on success, null if token not found
     */
    public function emailverification($token)
    {
        $check = User::where('email_verification_code', $token)->first();

        if (! is_null($check)) {
            $user = User::where('id', $check->id)->first();

            if ($user->email_verified == 1) {
                if (! is_null(Auth::id())) {
                    \Session::put('successmessage', 'E-mail Verified Successfully,Login Now');

                    return redirect()->to('/home');
                }

                \Session::put('successmessage', 'E-mail Verified Successfully,Login Now');

                return redirect()->to('login');
            }

            $user->email_verified = 1;
            $user->email_verified_at = Carbon::now();

            $user->save();
        }

        return null;
    }
}
