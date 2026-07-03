<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Services;

use App\Http\Resources\Notification as NotificationResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class NotificationReaderService
 *
 * Owns the read side shared byte-for-byte by all 5 NotificationController
 * copies (Admin, Accountant, Receptionist, Student, Teacher - Librarian
 * has no NotificationController of its own): the read/unread split used
 * by the notification list page, and the bell-icon dropdown summary.
 *
 * Fixes two bugs found while consolidating:
 * - Only Admin's showList() actually included the notification's `type`
 *   in its response; Accountant/Receptionist/Student/Teacher all computed
 *   it but never assigned it into the output array.
 * - Accountant/Receptionist/Student called count($notification->data['data'])
 *   with no cast; if that payload is ever a plain string rather than an
 *   array, count() throws a TypeError - which, unlike Exception, is NOT
 *   caught by the controllers' `catch (Exception $e)` blocks. Admin and
 *   Teacher already defensively cast to (array) first; that's the
 *   behavior kept here for everyone.
 */
class NotificationReaderService
{
    /**
     * @return array{read_list: \Illuminate\Http\Resources\Json\AnonymousResourceCollection, unread_list: \Illuminate\Http\Resources\Json\AnonymousResourceCollection}
     */
    public function groupedByReadStatus(int $userId): array
    {
        $unread = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->get();

        $read = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->whereNotNull('read_at')
            ->orderBy('read_at', 'ASC')
            ->get();

        return [
            'read_list' => NotificationResource::collection($read),
            'unread_list' => NotificationResource::collection($unread),
        ];
    }

    /**
     * The bell-icon dropdown: unread count plus the latest 5, formatted.
     * Matches the original's `if (Auth::user())` guard - returns an empty
     * array when there's no authenticated user rather than a partial one.
     */
    public function bellDropdownSummary(?User $user): array
    {
        if (! $user) {
            return [];
        }

        $array = [];
        $array['count'] = count($user->unreadNotifications);
        $notifications = $user->unreadNotifications->take(5);

        $i = 0;
        foreach ($notifications as $notification) {
            $val = '';
            $type = null;

            if ((count($notification->data) > 0) && (isset($notification->data['data']))) {
                if (count((array) $notification->data['data']) > 1) {
                    $val = $notification->data['data']['data'];
                    $type = $notification->data['data']['type'];
                } else {
                    $val = $notification->data['data'];
                    $type = null;
                }
            }

            $array['list'][$i]['notification_id'] = $notification['id'];
            $array['list'][$i]['data'] = $val;
            $array['list'][$i]['type'] = $type;
            $array['list'][$i]['date'] = $notification->created_at->diffForHumans();
            $i++;
        }

        return $array;
    }
}
