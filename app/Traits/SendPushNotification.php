<?php

/**
 * Sends push notifications through FCM for users and teachers.
 */

namespace App\Traits;

use Exception;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Log;

trait SendPushNotification
{
    /**
     * Send a push notification to a user via FCM.
     *
     * @param  array  $array  Payload containing at least 'type' and 'message'
     * @param  array|string  $usertoken  Single token or array of device tokens
     * @return bool Success status
     */
    public function sendNotification($array, $usertoken)
    {
        try {
            $messaging = app('firebase.messaging');

            $notification = Notification::create()
                ->withTitle($array['type'])
                ->withBody($array['message']);

            $message = CloudMessage::fromArray([
                'notification' => [
                    'title' => $array['type'],
                    'body' => $array['message'],
                ],
                'data' => [
                    'message' => $array['message'],
                    'type' => $array['type'],
                ],
                'webpush' => [
                    'fcmOptions' => [
                        'link' => url('/'),
                    ],
                ],
            ]);

            if (is_array($usertoken)) {
                $messaging->sendMulticast($message, $usertoken);
            } else {
                $messaging->send($message->withToken($usertoken));
            }

            return true;
        } catch (Exception $e) {
            Log::error('FCM notification send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a teacher-specific push notification via FCM.
     *
     * @param  array  $array  Payload containing at least 'type' and 'message'
     * @param  array|string  $usertoken  Single token or array of device tokens
     * @return bool Success status
     */
    public function sendTeacherNotification($array, $usertoken)
    {
        try {
            $messaging = app('firebase.messaging');

            $message = CloudMessage::fromArray([
                'notification' => [
                    'title' => $array['type'],
                    'body' => $array['message'],
                ],
                'data' => [
                    'message' => $array['message'],
                    'type' => $array['type'],
                ],
                'webpush' => [
                    'fcmOptions' => [
                        'link' => url('/'),
                    ],
                ],
            ]);

            if (is_array($usertoken)) {
                $messaging->sendMulticast($message, $usertoken);
            } else {
                $messaging->send($message->withToken($usertoken));
            }

            return true;
        } catch (Exception $e) {
            Log::error('FCM teacher notification send error: ' . $e->getMessage());
            return false;
        }
    }
}
