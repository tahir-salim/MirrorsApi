<?php

namespace App\Http\Controllers;

use App\Libraries\SMSSender;
use App\Models\Country;
use App\Models\Settings;
use App\Models\TalentDetails;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{
    public function store(Request $request){

        // general fields validation for both users
        $validation =  [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'role_id' => 'required|integer',
            'password' => 'required|min:6'
        ];

        // if user is talent the required fields
        if ($request->role_id == User::ROLE_TALENT){
            // validate for talent signup field
            $validation =  [
                'bank_name' => 'required|string',
                'bank_account_owner' => 'required|string',
                'bank_iban' => 'required'
            ];
        }

        $validation =  Validator::make($request->all(), $validation);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        // get country from phone number
        $country = (new \App\Models\Country)->getCountryFromPhone($request->phone);

        // if phone number not belongs to any country
        if (!$country){
            return $this->formatResponse('error', 'country-not-found', null, 400);
        }

        $user = User::where(function ($query) use ($request){
                $query->where('email', $request['email'])
                    ->orWhere('phone', $request['phone']);
        })
            ->first();

        if ($user){

            if ($user->email_verified_at || $user->phone_verified_at){

                return $this->formatResponse(
                    'error',
                    'user-already-exists',
                    null,
                    400
                );
            }

            if ($user->is_blocked){
                return $this->formatResponse('error', 'user-is-blocked', null,400);
            }
            // unverified user update
            $user->name = $request->name;
            $user->email = $request->email;
            $user->country_id = $country?$country['id']:null;
            $user->phone = $request->phone;
            $user->role_id = $request->role_id;
            $user->password = Hash::make($request->password);
            $user->save();

            // if user is talent
            if ($request->role_id == User::ROLE_TALENT) {
                $user->talentDetail()->update([
                    'bank_name' => $request->bank_name,
                    'bank_account_owner' => $request->bank_account_owner,
                    'bank_iban' => $request->bank_iban,
                    'status' => TalentDetails::ACTIVE,
                ]);
            }

            $token = rand(1000, 9999);

            if ($token) {
                // remove old user verification
                UserVerification::where(function ($query) use ($request){
                    $query->where('email', $request['email'])
                        ->orWhere('phone', $request['phone']);
                })
                    ->delete();

                UserVerification::create([
                    'email' => $user->email,
                    'token' => $token,
                    'country_id' => $country?$country['id']:null,
                    'phone' => $user->phone,
                    'user_id' => $user->id,
                    'status' => UserVerification::STATUS_PENDING
                ]);

                $msg = 'Your Verification code is ' . $token;
                $isMessageSent = SMSSender::send($request->phone, $msg);

                if (!$isMessageSent) {
                    return $this->formatResponse('error', 'invalid-number', "Sms was not sent", 400);
                }

                $sentThrough = 'SMS';
                return $this->formatResponse('success', 'sent', ['sent_through' => $sentThrough]);
            }
        }
        // create new unverified user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->country_id = $country?$country['id']:null;
        $user->phone = $request->phone;
        $user->role_id = $request->role_id;
        $user->password = Hash::make($request->password);
        $user->save();
        // if user is talent
        if ($request->role_id == User::ROLE_TALENT) {

            $talentDetail = new TalentDetails();
            $talentDetail->user_id = $user->id;
            $talentDetail->bank_name = $request->bank_name;
            $talentDetail->bank_account_owner = $request->bank_account_owner;
            $talentDetail->bank_iban = $request->bank_iban;
            $talentDetail->status = TalentDetails::INACTIVE;
            $talentDetail->save();
        }

        // create otp
        $token = rand(1000, 9999);

        if ($token) {

            UserVerification::create([
            'email' => $request->email,
            'token' => $token,
            'country_id' => $country?$country['id']:null,
            'phone' => $request->phone,
            'user_id' => $user->id,
            'status' => UserVerification::STATUS_PENDING
        ]);

            $msg = 'Your Verification code is ' . $token;
            $isMessageSent = SMSSender::send($request->phone, $msg);

            if (!$isMessageSent) {
                return $this->formatResponse('error', 'invalid-number', "Sms was not sent", 400);
            }

            $sentThrough = 'SMS';
            return $this->formatResponse('success', 'sent', ['sent_through' => $sentThrough]);
        }
    }
}
