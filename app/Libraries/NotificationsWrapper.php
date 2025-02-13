<?php

namespace App\Libraries;
use App\Models\Notification;


class NotificationsWrapper
{

    public static function bookingAccepted($request)
    {
        $title = 'Booking Request Accepted';
        $body = 'Your booking with ' . $request->talentUser->name . ' has been accepted';
        $notification = $request->notifications()->create([
            'user_id' => $request->user->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_BOOKING_ACCEPTED,
        ]);

        // send notification
        if($request->user->device_token) {
              FirebaseFCM::push($request->user->device_token,
                          $title, $body,
                          ['type' => 'booking_accepted', 'notification_id' => $notification->id]
                         );
        }
    }

    public static function bookingCanceled($request)
    {
        $title = 'Booking Request Canceled';
        $body = 'Your booking with ' . $request->user->name . ' has been canceled';
        $notification = $request->notifications()->create([
            'user_id' => $request->talentUser->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_BOOKING_CANCELED,
        ]);

        // send notification
        if($request->talentUser->device_token) {
            FirebaseFCM::push($request->talentUser->device_token,
                          $title, $body,
                          ['type' => 'booking_canceled', 'notification_id' => $notification->id]
                         );
        }

    }

    public static function bookingRejected($request)
    {
        $title = 'Booking Request Rejected';
        $body = 'Your booking with ' . $request->talentUser->name . ' has been rejected';
        $notification = $request->notifications()->create([
            'user_id' => $request->user->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_BOOKING_REJECTED,
        ]);

        // send notification
            if($request->user->device_token) {
                  FirebaseFCM::push($request->user->device_token,
                          $title, $body,
                          ['type' => 'booking_rejected', 'notification_id' => $notification->id]
                         );
        }
    }

    public static function userComment($request)
    {
        $title = 'New Request Comment';
        $body = $request->user->name . ' wrote new comments on your booking request';
        $notification = $request->notifications()->create([
            'user_id' => $request->talentUser->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_BOOKING_REJECTED,
        ]);

        // send notification
         if($request->talentUser->device_token) {
             FirebaseFCM::push($request->talentUser->device_token,
                          $title, $body,
                          ['type' => 'user_comment', 'notification_id' => $notification->id]
                         );
        }
    }

     public static function talentComment($request)
    {
        $title = 'New Request Comment';
        $body = $request->talentUser->name . ' wrote new comments on your booking request';
        $notification = $request->notifications()->create([
            'user_id' => $request->user->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_BOOKING_REJECTED,
        ]);

        // send notification
        if($request->user->device_token) {
            FirebaseFCM::push($request->user->device_token,
                        $title, $body,
                        ['type' => 'talent_comment', 'notification_id' => $notification->id]
                        );
        }
    }

    public static function bookingReview($request)
    {
        $title = 'New Booking Review';
        $body = $request->user->name . ' has reviewed their booking with you';
        $notification = $request->notifications()->create([
            'user_id' => $request->talentUser->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_BOOKING_REVIEW,
        ]);

        // send notification
         if($request->talentUser->device_token) {
              FirebaseFCM::push($request->talentUser->device_token,
                          $title, $body,
                          ['type' => 'booking_review', 'notification_id' => $notification->id]
                         );
        }

    }

    public static function newBooking($request)
    {
        $title = 'New Booking Request';
        $body = $request->user->name . ' has requested a new booking with you';
        $notification = $request->notifications()->create([
            'user_id' => $request->talentUser->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_NEW_BOOKING,
        ]);

        // send notification
        if($request->talentUser->device_token) {
                 FirebaseFCM::push($request->talentUser->device_token,
                          $title, $body,
                          ['type' => 'new_booking', 'notification_id' => $notification->id]
                         );
        }
    }

    public static function bookingUpdate($request)
    {
        $title = 'Booking Request Update';
        $body = $request->user->name . ' has posted new booking details';
        $notification = $request->notifications()->create([
            'user_id' => $request->talentUser->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_BOOKING_UPDATE,
        ]);

        // send notification
        if($request->talentUser->device_token) {
            FirebaseFCM::push($request->talentUser->device_token,
                        $title, $body,
                        ['type' => 'booking_update', 'notification_id' => $notification->id]
                        );
        }

    }

    public static function reviewInquiry($request)
    {
        $title = 'Request Completed';
        $body = 'Submit your feedback regarding your request with ' . $request->talentUser->name;
        $notification = $request->notifications()->create([
            'user_id' => $request->user->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_REVIEW_INQUIRY,
        ]);

        // send notification
        if($request->user->device_token) {
              FirebaseFCM::push($request->user->device_token,
                          $title, $body,
                          ['type' => 'review_inquiry', 'notification_id' => $notification->id]
                         );
        }
    }

    public static function requestPaid($request)
    {
        $title = 'Request Paid';
        $body = $request->user->name . ' has fully paid the request amount. Start chatting with them now';
        $notification = $request->notifications()->create([
            'user_id' => $request->talentUser->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_REQUEST_PAID,
        ]);

        // send notification
        if($request->talentUser->device_token) {
              FirebaseFCM::push($request->user->device_token,
                          $title, $body,
                          ['type' => 'request_paid', 'notification_id' => $notification->id]
                         );
        }
    }

    public static function requestChargePaid($request)
    {
        $title = 'Request Extra Charge Paid';
        $body = $request->user->name . ' has fully paid the requested extra charge amount.';
        $notification = $request->notifications()->create([
            'user_id' => $request->talentUser->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_REQUEST_CHARGE_PAID,
        ]);

        // send notification
        if($request->talentUser->device_token) {
              FirebaseFCM::push($request->user->device_token,
                          $title, $body,
                          ['type' => 'request_charge_paid', 'notification_id' => $notification->id]
                         );
        }
    }

    public static function newRequestCharge($request)
    {
        $title = 'Extra Charges Requested';
        $body = $request->talentUser->name . ' has added extra charges to your request.';
        $notification = $request->notifications()->create([
            'user_id' => $request->user->id,
            'title' => $title,
            'body' => $body,
            'action_type' => Notification::TYPE_NEW_REQUEST_CHARGE,
        ]);

        // send notification
        if($request->talentUser->device_token) {
              FirebaseFCM::push($request->user->device_token,
                          $title, $body,
                          ['type' => 'new_request_charge', 'notification_id' => $notification->id]
                         );
        }
    }
}
