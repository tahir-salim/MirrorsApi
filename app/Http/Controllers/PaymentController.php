<?php

namespace App\Http\Controllers;

use App\Libraries\NotificationsWrapper;
use App\Libraries\TapPayment;
use App\Models\BookingRequest;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\RequestCharges;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function verifyTapPayment(Request $request)
    {
        abort_if(!$request->tap_id, 400, 'verification_failed');
        //get transaction record
        $transaction = Transaction::where('tap_charge_id', $request->tap_id)->where('tap_response_code', 100)->first();

        abort_if(!$transaction, 400, 'verification_failed');

        // get tap charge object
        $result = TapPayment::getCharge($transaction->tap_charge_id);

        $charge = $result['data'];

        if ($charge) {
            // update the transaction record
            $transaction->update([
                'tap_customer_id' => $charge->customer->id ?? null,
                'tap_response_code' => $result['code'],
                'tap_response' => $charge,
                'usd_amount' => $charge->amount,
                'currency' => $charge->currency,
                'tap_status' => $charge->status
            ]);
        }

        $bookingRequest = BookingRequest::where('transaction_id', $transaction->id)->first();

        if ($charge && $charge->status === Transaction::CAPTURED) {

            // if payment is captured then booking request is at processed
            $bookingRequest->processed_at = now();
            $bookingRequest->payment_status = BookingRequest::STATUS_PAID;
            $bookingRequest->save();

            $transaction->update([
                'paid_at' => now(),
                'status' => Transaction::COMPLETED,
                'is_success' => true,
            ]);

            // Check to create a new chat
            $chatUser = ChatUser::where('user_id', $bookingRequest->user_id)
            ->whereHas('chat', function ($query) use ($bookingRequest) {
                $query->whereHas('chatUsers', function ($query) use($bookingRequest) {
                    $query->where('user_id', $bookingRequest->talent_user_id);
                });
            })
            ->get()
            ->pluck('chat');

            if ($chatUser->isEmpty()) {
                $chat = new Chat();
                $chat->save();

                $chat->chatUsers()->create(['user_id' => $bookingRequest->user_id]);
                $chat->chatUsers()->create(['user_id' => $bookingRequest->talent_user_id]);
            } else {
                $chatUser->first()->update(['is_locked' => false]);

            }

            // Send notification to talent
            NotificationsWrapper::requestPaid($bookingRequest);
            return redirect('/tap/payment/success');
        } else {

            $transaction->update([
                'paid_at' => null,
                'status' => Transaction::CANCEL,
                'is_success' => false,
            ]);

            return redirect('/tap/payment/failed');
        }
    }

    public function verifyRequestChargesPayment(Request $request)
    {
        abort_if(!$request->tap_id, 400, 'verification_failed');
        //get transaction record
        $transaction = Transaction::where('tap_charge_id', $request->tap_id)->where('tap_response_code', 100)->first();

        abort_if(!$transaction, 400, 'verification_failed');

        // get tap charge object
        $result = TapPayment::getCharge($transaction->tap_charge_id);

        $charge = $result['data'];

        if ($charge) {
            // update the transaction record
            $transaction->update([
                'tap_customer_id' => $charge->customer->id ?? null,
                'tap_response_code' => $result['code'],
                'tap_response' => $charge,
                'usd_amount' => $charge->amount,
                'currency' => $charge->currency,
                'tap_status' => $charge->status
            ]);
        }

        $requestCharge = RequestCharges::where('transaction_id', $transaction->id)->first();

        if ($charge && $charge->status === Transaction::CAPTURED) {

            $requestCharge->payment_status = BookingRequest::STATUS_PAID;
            $requestCharge->save();

            $transaction->update([
                'paid_at' => now(),
                'status' => Transaction::COMPLETED,
                'is_success' => true,
            ]);

            NotificationsWrapper::requestChargePaid($requestCharge->bookingRequest);
            return redirect('/tap/payment/success');
        } else {

            $transaction->update([
                'paid_at' => null,
                'status' => Transaction::CANCEL,
                'is_success' => false,
            ]);

            return redirect('/tap/payment/failed');
        }
    }

    public function showFailed()
    {
        return view('failed');
    }

    public function showSuccess()
    {
        return view('success');
    }
}
