<?php

namespace App\Http\Controllers;

use App\Libraries\TapPayment;
use App\Models\BookingRequest;
use App\Models\RequestCharges;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RequestChargesController extends Controller
{
    public function storeRequestCharges(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_request_id' => 'required|integer',
            'title' => 'required|max:150|string',
            'description' => 'required|string',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validator->errors()->first(),
                400
            );
        }

        $bookingRequest = BookingRequest::find($request->booking_request_id);

        if (!$bookingRequest) {
            return $this->formatResponse('error', 'booking-request-is-not-found', null, 404);
        }

        $bookingRequest->requestCharges()->create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return $this->formatResponse('success', 'add-request-charges-successfully', $bookingRequest->fresh());

    }

    public function paymentRequestCharges($requestChargesId)
    {
        $user = User::isNotBlocked()
            ->isVerified()
            ->isUser()
            ->find(Auth::id());

        if (!$user) {
            return $this->formatResponse('error', 'unauthorized-user', null, 404);
        }

        $requestCharges = RequestCharges::where('paid_with_request', RequestCharges::PAID_WITHOUT_REQUEST)
            ->with([
                'bookingRequest',
                'transaction',
            ])
            ->find($requestChargesId);

        if (!$requestCharges) {
            return $this->formatResponse('error', 'request-charges-not-found', null, 403);
        }

        $transaction = $requestCharges->transaction()
        ->where('status',Transaction::PENDING)
        ->where('tap_status',Transaction::INITIATED)
        ->first();

        if ($requestCharges->transaction && $requestCharges->transaction->tap_status == Transaction::CAPTURED) {
            return $this->formatResponse('error', 'you-already-paid-for-this-request-charges', $requestCharges->transaction, 403);
        }

        $transaction_price = $requestCharges->total_price ?? $requestCharges->price;
        // tap payment
        $result = TapPayment::createCharge(
            'USD',
            $user['name'],
            $user['phone'],
            config('app.url') . '/payment/request-charges/verify',
            $transaction_price,
            TapPayment::SOURCE_CARD
        );

        // create a new transaction if is not exist
        if (!$transaction) {
            $transaction = new Transaction();
            $transaction->user_id = Auth::id();
            $transaction->status = Transaction::PENDING;
            $transaction->is_success = false;
        }

        $transaction->currency = 'USD';
        $transaction->origin = null;
        $transaction->amount = $transaction_price;
        $transaction->usd_amount = $transaction_price;

        if ($result && isset($result['code'])) {

            $tapResponse = isset($result['data']) ? $result['data'] : null;

            if ($tapResponse) {

                $transaction->tap_response = $tapResponse;
                $transaction->tap_charge_id = $tapResponse->id;
                $transaction->tap_status = $tapResponse->status;
                $transaction->payment_link = optional($tapResponse->transaction)->url ?? null;
                $transaction->is_success = true;
                $transaction->tap_response_code = $result['code'];

            }
        }

        $transaction->save();

        $requestCharges->transaction_id = $transaction->id;
        $requestCharges->save();

        if (!$tapResponse) {
            return $this->formatResponse(
                false,
                'request-charges-insert-payment-failed',
                [
                    'request_chargres' => $requestCharges,
                    'tap_response' => $tapResponse,
                ],
                400
            );
        }

        return $this->formatResponse(
            true,
            'request-charges-insert',
            [
                'request_charges' => $requestCharges,
                'success_url' => config('app.url') . '/tap/payment/success',
                'failed_url' => config('app.url') . '/tap/payment/failed',
                'payment_url' => optional($tapResponse->transaction)->url,
            ]
        );

    }
}
