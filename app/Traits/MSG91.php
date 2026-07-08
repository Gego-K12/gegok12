<?php

namespace App\Traits;

use App\Models\Reminder;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * MSG91 Trait
 *
 * Provides SMS sending functionality via MSG91 gateway API.
 * Supports:
 * - Standard SMS sending
 * - OTP generation and delivery
 * - Emergency SMS notifications
 *
 * Configuration via environment variables:
 * - SMS_GATEWAY_API_KEY: API authentication key
 * - SMS_GATEWAY_SENDER_ID: Sender ID for SMS
 * - SMS_GATEWAY_ROUTE_NO: Route number for message prioritization
 */
trait MSG91
{
    private $RESPONSE_TYPE = 'json';

    /**
     * Send SMS via MSG91 Gateway
     *
     * Sends an SMS message to one or more mobile numbers using MSG91 API.
     * Updates Reminder records with API response after sending.
     * Uses environment variables for API credentials.
     *
     * @param  string  $content  The message content to send
     * @param  string  $to  Mobile number(s) to send to (comma-separated for multiple)
     * @return string API response from MSG91 gateway
     *
     * @throws Exception Logs exceptions without throwing
     */
    public function sendSMS($content, $to)
    {
        try {
            $API_KEY = env('SMS_GATEWAY_API_KEY');
            $SENDER_ID = env('SMS_GATEWAY_SENDER_ID');
            $ROUTE_NO = env('SMS_GATEWAY_ROUTE_NO');

            // URL encode message for API transmission
            $message = urlencode($content);

            // Prepare POST parameters for cURL request
            $postData = [
                'authkey' => $API_KEY,
                'mobiles' => $to,
                'message' => $message,
                'sender' => $SENDER_ID,
                'route' => $ROUTE_NO,
                'response' => $this->RESPONSE_TYPE,
            ];

            $curl = curl_init();

            try {
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.msg91.com/api/sendhttp.php?&mobiles='.$postData['mobiles'].'&authkey='.$postData['authkey'].'&route='.$postData['route'].'&sender='.$postData['sender'].'&message='.$postData['message'].'&country=91',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                if ($err) {
                    return 'cURL Error #:'.$err;
                }

                // Update reminder records with SMS response
                $now = date('Y-m-d');
                $queueList = Reminder::where('via', '=', 'sms')
                    ->where('executed_at', '=', $now)
                    ->get();

                foreach ($queueList as $queue) {
                    Reminder::where('id', $queue->id)
                        ->update(['sms_response' => $response]);
                }

                return $response;
            } finally {
                // cURL resource automatically cleaned up in PHP 8.0+
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send OTP via SMS
     *
     * Generates and sends a One-Time Password (OTP) to the specified mobile number.
     * Message format: "Welcome to GegoK12. Your OTP is : [OTP]"
     * Uses reminder API credentials from environment variables.
     *
     * @param  string  $OTP  The OTP code to send
     * @param  string  $mobileNumber  Mobile number to send OTP to
     * @return string API response from MSG91 gateway
     *
     * @throws Exception Logs exceptions without throwing
     */
    public function getOTP($OTP, $mobileNumber)
    {
        try {
            $API_KEY = env('SMS_GATEWAY_API_KEY');
            $SENDER_ID = env('SMS_GATEWAY_SENDER_ID');
            $ROUTE_NO = env('SMS_GATEWAY_ROUTE_NO');

            // Prepare OTP message with welcome text
            $message = urlencode("Welcome to GegoK12. Your OTP is : $OTP");

            // Prepare POST parameters for API request
            $postData = [
                'authkey' => $API_KEY,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => $SENDER_ID,
                'route' => $ROUTE_NO,
                'response' => $this->RESPONSE_TYPE,
            ];

            $curl = curl_init();

            try {
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.msg91.com/api/sendhttp.php?&mobiles='.$postData['mobiles'].'&authkey='.$postData['authkey'].'&route='.$postData['route'].'&sender='.$postData['sender'].'&message='.$postData['message'].'&country=91',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                if ($err) {
                    return 'cURL Error #:'.$err;
                }

                return $response;
            } finally {
                // cURL resource automatically cleaned up in PHP 8.0+
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Send Emergency SMS Notification
     *
     * Sends an emergency notification SMS to one or more mobile numbers.
     * Message format: "GegoK12. Message : [message_content]"
     * Uses reminder API credentials from environment variables.
     *
     * @param  string  $message_content  The emergency notification message
     * @param  string  $mobileNumber  Mobile number(s) to send to (comma-separated for multiple)
     * @return string API response from MSG91 gateway
     *
     * @throws Exception Logs exceptions without throwing
     */
    public function emergencySMS($message_content, $mobileNumber)
    {
        try {
            $API_KEY = env('SMS_GATEWAY_API_KEY');
            $SENDER_ID = env('SMS_GATEWAY_SENDER_ID');
            $ROUTE_NO = env('SMS_GATEWAY_ROUTE_NO');

            // Prepare emergency message with app branding
            $message = urlencode("GegoK12. Message : $message_content");

            // Prepare POST parameters for API request
            $postData = [
                'authkey' => $API_KEY,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => $SENDER_ID,
                'route' => $ROUTE_NO,
                'response' => $this->RESPONSE_TYPE,
            ];

            $curl = curl_init();

            try {
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.msg91.com/api/sendhttp.php?&mobiles='.$postData['mobiles'].'&authkey='.$postData['authkey'].'&route='.$postData['route'].'&sender='.$postData['sender'].'&message='.$postData['message'].'&country=91',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                if ($err) {
                    return 'cURL Error #:'.$err;
                }

                return $response;
            } finally {
                // cURL resource automatically cleaned up in PHP 8.0+
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
