<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class NotificationWriterService
 *
 * Owns the "mark as read" mutation shared byte-for-byte by all 5
 * NotificationController copies.
 */
class NotificationWriterService
{
    public function markRead(int $userId, $notificationId): string
    {
        if ($notificationId != 'all') {
            DB::table('notifications')
                ->where('id', $notificationId)
                ->where('notifiable_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => Carbon::now()]);

            return 'Notification Read Successfully';
        }

        DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);

        return 'All Notifications Read Successfully';
    }
}
