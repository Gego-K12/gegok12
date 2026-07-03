<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class SendTeacherNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;

    public string $token;

    public function __construct(array $data, string $token)
    {
        $this->queue = 'notification';
        $this->data = $data;
        $this->token = $token;

    }

    public function via($notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {

        try {
            $type = $this->data['type'] ?? 'Notification';
            $message = $this->data['message'] ?? '';
            $credentialsArray = config('firebase.projects.appteacher.credentials');
            $factory = (new Factory)->withServiceAccount($credentialsArray);
            $messaging = $factory->createMessaging();

            return FcmMessage::create()
                ->usingClient($messaging)
                ->token($this->token)
                ->data([
                    'type' => $type,
                    'message' => $message,
                ])
                ->notification(
                    FcmNotification::create($type, $message)
                )
                ->custom([
                    'android' => [
                        'notification' => [
                            'color' => '#0A0A0A',
                            'sound' => 'default',
                        ],
                        'fcm_options' => [
                            'analytics_label' => 'analytics',
                        ],
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                            ],
                        ],
                        'fcm_options' => [
                            'analytics_label' => 'analytics_ios',
                        ],
                    ],
                ]);

        } catch (\Exception $e) {

            Log::error('FCM Notification Error', [
                'token' => $this->token,
                'data' => $this->data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Optional: prevent job from crashing
            return FcmMessage::create()
                ->token($this->token)
                ->data([
                    'type' => 'Error',
                    'message' => 'Notification failed',
                ]);
        }
    }

    // public function fcmProject($notifiable, $message): ?string
    // {
    //     return 'appteacher';
    // }
}
