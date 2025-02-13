<?php

namespace App\Http\Controllers\TalentApi;

use App\Http\Controllers\Controller;
use App\Libraries\NotificationsWrapper;
use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RequestCommentController extends Controller
{
    public function store(Request $request, $requestID){

        $validation = Validator::make($request->all(), [
            'comment' => 'required|min:3|max:2000',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $talent = User::isNotBlocked()->isTalent()->find(Auth::id());

        if (!$talent){
            return $this->formatResponse('error', 'talent-not-found', null, 404);
        }

        $bookingRequest = BookingRequest::find($requestID);

        if (!$bookingRequest){
            return $this->formatResponse('error', 'request-not-found', null, 404);
        }

        if ($bookingRequest->status != BookingRequest::PENDING){
            return $this->formatResponse('error','your-request-is-not-pending', null, 403);
        }

        if($bookingRequest->talent_user_id != $talent->id) {
            return $this->formatResponse('error', 'does-not-belong-to-user', null, 403);
        }

        $talent->requestComments()->attach($bookingRequest->id, ['comment' => $request->comment]);
        NotificationsWrapper::talentComment($bookingRequest);
        return $this->formatResponse('success', 'comment-created', null);
    }
}
