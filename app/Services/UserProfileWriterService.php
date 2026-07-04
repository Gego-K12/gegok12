<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\User;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Traits\LogActivity;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Log;

/**
 * Class UserProfileWriterService
 *
 * Owns the write side shared by the UserProfileController copies:
 * - changePassword(): identical logic in Admin/Accountant/Receptionist/
 *   Student/Teacher. Admin/Receptionist/Student/Teacher had no try/catch
 *   at all (an exception would 500); Accountant wrapped it in try/catch +
 *   Log::info. Unified onto Accountant's behavior per product decision
 *   2026-07-04.
 * - updateAvatarFromBase64(): the base64-decode-and-store strategy shared
 *   by Accountant/Receptionist/Teacher (Admin uses a different multipart
 *   Storage::putFile strategy and is deliberately left in its own
 *   controller - not duplicated elsewhere, so nothing to consolidate).
 *   Fixes a real bug: the uploaded image's actual type (jpg/jpeg/png - all
 *   three are allowed by TeacherAvatarAddRequest's validation) was computed
 *   into $image_type and then never used - every avatar was saved with a
 *   hardcoded ".jpg" filename regardless of its real type. Also fixes
 *   `$res['message']` being referenced in the activity-log call outside the
 *   `if ($request->avatar != '')` block that set it (in practice avatar is
 *   `required` by validation, so this branch is always taken - but the
 *   consolidated version no longer relies on that to avoid an undefined-
 *   variable read).
 */
class UserProfileWriterService
{
    use Common;
    use LogActivity;

    /**
     * @return array{message: string}|null null on a caught exception.
     */
    public function changePassword(int $userId, string $newPassword): ?array
    {
        try {
            $user = User::find($userId);
            $hashedPassword = $user->password;

            if ($hashedPassword != '') {
                $user->password = Hash::make($newPassword);
                $user->save();

                $this->logActivity($user, 'Changed Profile Password.', LOGNAME_CHANGE_PASSWORD);
            }

            return ['message' => __('admin_userprofile.password_update')];
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return null;
        }
    }

    /**
     * @return array{message: string}|null null on a caught exception.
     */
    public function updateAvatarFromBase64(int $userId, string $base64Avatar): ?array
    {
        try {
            $userprofile = Userprofile::where('user_id', $userId)->first();
            $user = User::find($userId);

            $imageParts = explode(';base64,', $base64Avatar);
            $imageTypeAux = explode('image/', $imageParts[0]);
            $imageType = $imageTypeAux[1];
            $imageBase64 = base64_decode($imageParts[1]);

            $location = $user->school->slug.'/uploads/admin/teacher/avatar/';
            $file = uniqid().'.'.$imageType;
            $uploadPath = $location.$file;

            $this->putContents($uploadPath, $imageBase64);

            $userprofile->avatar = $uploadPath;
            $userprofile->save();

            $message = __('admin_userprofile.update_avatar');
            $this->logActivity($userprofile, $message, LOGNAME_CHANGE_AVATAR);

            return ['message' => $message];
        } catch (Exception $e) {
            Log::info($e->getMessage());

            return null;
        }
    }

    private function logActivity($performedOn, string $message, string $logName): void
    {
        $ip = $this->getRequestIP();
        $this->doActivityLog(
            $performedOn,
            Auth::user(),
            ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
            $logName,
            $message
        );
    }
}
