<?php

namespace App\Libraries;

use App\Models\SmsLog;
use Nexmo\Laravel\Facade\Nexmo;

class SMSSender
{
    // returns user ID
    public static function send($phone, $message)
    {
        if (!config('app.enable_sms')) {
            static::logSms($phone, $message, true);
            return true;
        }

        if (!$phone) {
            static::logSms($phone, $message);
            return false;
        }

        // Check if its a valid phone number
        try {
         Nexmo::insights()->basic($phone);
        } catch (\Exception $ex) {
         (new \App\Exceptions\Handler(app()))->report($ex);
            static::logSms($phone, $message);
            return false;
        }


        try {
                Nexmo::message()->send([
                    'to' => $phone,
                    'from' => '16105552344',
                    'text' => $message,
                ]);


        } catch (\Exception $ex) {
            (new \App\Exceptions\Handler(app()))->report($ex);
            static::logSms($phone, $message);
            return false;
        }

        static::logSms($phone, $message, true);
        return true;

    }

    public static function logSms($phone, $message, $sent = false)
    {
        // add sms to logs
        SmsLog::create([
            'phone' => $phone,
            'message_body' => $message,
            'sent' => $sent,
        ]);
    }
}
