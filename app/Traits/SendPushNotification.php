<?php

/**
 * Sends push notifications through FCM for users and teachers.
 */

namespace App\Traits;

use Exception;
use FCM;
use LaravelFCM\Message\DownstreamResponse;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Log;

trait SendPushNotification
{
    /**
     * Send a push notification to a user via FCM.
     *
     * @param  array  $array  Payload containing at least 'type' and 'message'
     * @param  array|string  $usertoken  Single token or array of device tokens
     * @return DownstreamResponse|null Response object or null on failure
     */
    public function sendNotification($array, $usertoken)
    {
        try {

            config(['fcm.http.server_key' => env('FCM_SERVER_KEY')]);
            config(['fcm.http.sender_id' => env('FCM_SENDER_ID')]);
            $optionBuilder = new OptionsBuilder;
            $optionBuilder->setTimeToLive(60 * 20);
            $notificationBuilder = new PayloadNotificationBuilder($array['type']);
            $notificationBuilder->setBody($array['message'])
                ->setTitle($array['type'])
                ->setSound('default');
            $dataBuilder = new PayloadDataBuilder;
            $dataBuilder->addData(['message' => $array['message'], 'type' => $array['type']]);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            $token = $usertoken;
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            if ($downstreamResponse->numberSuccess()) {
                return $downstreamResponse;
            } else {
                return $downstreamResponse;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send a teacher-specific push notification via FCM.
     *
     * @param  array  $array  Payload containing at least 'type' and 'message'
     * @param  array|string  $usertoken  Single token or array of device tokens
     * @return DownstreamResponse|null Response object or null on failure
     */
    public function sendTeacherNotification($array, $usertoken)
    {
        try {
            config(['fcm.http.server_key' => env('FCM_TEACHER_SERVER_KEY')]);
            config(['fcm.http.sender_id' => env('FCM_TEACHER_SENDER_ID')]);
            $optionBuilder = new OptionsBuilder;
            $optionBuilder->setTimeToLive(60 * 20);
            $notificationBuilder = new PayloadNotificationBuilder($array['type']);
            $notificationBuilder->setBody($array['message'])
                ->setTitle($array['type'])
                ->setSound('default');
            $dataBuilder = new PayloadDataBuilder;
            $dataBuilder->addData(['message' => $array['message'], 'type' => $array['type']]);
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            $token = $usertoken;
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            if ($downstreamResponse->numberSuccess()) {
                return $downstreamResponse;
            } else {
                return $downstreamResponse;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
