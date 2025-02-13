<?php

namespace App\Http\Controllers;

use App\Libraries\NotificationsWrapper;
use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RequestCommentController extends Controller
{
    public function store(Request $request ,$requestID){

        $validation = Validator::make($request->all(), [
            'comment' => 'required|min:3|max:2000',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $user = User::isNotBlocked()->isUser()->find(Auth::id());

        if (!$user){
            return $this->formatResponse('error', 'user-not-found', null, 404);
        }

        $bookingRequest = BookingRequest::find($requestID);

        if (!$bookingRequest){
            return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        if ($bookingRequest->status != BookingRequest::PENDING){
            return $this->formatResponse('error','your-request-is-not-pending', null, 403);
        }

        if($bookingRequest->user_id != $user->id) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        $user->requestComments()->attach($bookingRequest->id, ['comment' => $request->comment]);

        NotificationsWrapper::userComment($bookingRequest);
        return $this->formatResponse('success', 'comment-created', null);
    }
}
