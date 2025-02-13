<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    //
    public function getNotifications(Request $request)
    {
        $userNotifications = Notification::where('user_id', Auth::id())
                                         ->where(function ($query) use ($request){
                                        $query->where('title', 'like', "%$request->text%")
                                            ->orWhere('body', 'like', "%$request->text%");
                                        })
                                         ->orderByDesc('id')
                                         ->paginate(20);

        return $this->formatResponse('success','get-notifications',$userNotifications);
    }

    public function readNotifications(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())
                                    ->where('is_read', false);

        if(!$request->notificationsId) {
            //Mark them all as read
            $notifications->update([
                'is_read' => true,
            ]);
        } else {
            $notifications
                ->whereIn('id', $request->notificationsId)
                ->update([
                    'is_read' => true,
                ]);
        }

        return $this->formatResponse('success','read-notifications');
    }
}
