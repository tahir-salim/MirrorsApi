<?php

namespace App\Libraries;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseFCM
{
    public static function push($tokens, $title, $body, $data)
    {
        if (config('app.enable_push_notifications')) {

            $serviceAccountCredentials = base_path() . '/keys/mirrors-app-firebase-adminsdk-akgd9-30452f1b14.json';
            $factory = (new Factory)->withServiceAccount($serviceAccountCredentials);
            $messaging = $factory->createMessaging();
            $notification = Notification::create($title, $body);

            $message = CloudMessage::new ();
            $message = $message
                ->withNotification($notification)
                ->withDefaultSounds();
            if ($data) {
                $message = $message->withData($data);
            }

            $tokens = is_array($tokens) ? $tokens : [$tokens];
            $sendReport = $messaging->sendMulticast($message, $tokens);

            return $sendReport;

        }

        return false;
    }

}
