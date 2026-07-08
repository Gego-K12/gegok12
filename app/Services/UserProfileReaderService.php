<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Models\Userprofile;
use App\Traits\Common;

/**
 * Class UserProfileReaderService
 *
 * Owns the read side shared byte-for-byte by Admin/Accountant/Receptionist/
 * Teacher's UserProfileController::getavatar() (Student and Api\Userprofile
 * have no avatar-lookup endpoint of their own).
 */
class UserProfileReaderService
{
    use Common;

    /**
     * @return array{avatar?: string, id?: int}
     */
    public function avatar(int $userId): array
    {
        $userprofile = Userprofile::where('user_id', $userId)->first();
        $array = [];

        if ($userprofile) {
            $array['avatar'] = $this->getFilePath($userprofile->avatar);
            $array['id'] = $userprofile->id;
        }

        return $array;
    }
}
