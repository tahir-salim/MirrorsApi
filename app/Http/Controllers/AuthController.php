<?php

namespace App\Http\Controllers;

use App\Libraries\SMSSender;
use App\Models\TalentDetails;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // resend otp
    public function sendOtp(Request $request){

        $validate = Validator::make($request->all(), [
            'phone' => 'required'
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'required-fields', null, 400);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user){
            return $this->formatResponse('error', 'user-not-found', null, 400);
        }

        if ($user->is_blocked){
            return $this->formatResponse('error', 'user-is-blocked',null, 403);
        }

        $otpValidation = UserVerification::where('phone', $user->phone)->first();

        if ($otpValidation){

            $token = rand(1000, 9999);
            $otpValidation->update(['token' => $token,'status' => UserVerification::STATUS_PENDING]);

            $msg = 'Your Verification code is ' . $token;
            $isMessageSent = SMSSender::send($user->phone, $msg);

            if (!$isMessageSent) {
                return $this->formatResponse('error', 'invalid-number', "Sms was not sent", 400);
            }

            $sentThrough = 'SMS';

            return $this->formatResponse('success', 'sent', ['sent_through' => $sentThrough]);

        }
        else{
            // this code may temporarily use if user is verified and not found in user verification
            if ($user->phone_verified_at && $user->phone){

                $token = rand(1000, 9999);

                $userVerification = new UserVerification();
                $userVerification->email = $user['email'];
                $userVerification->country_id = $user['country_id'];
                $userVerification->phone = $user['phone'];
                $userVerification->token = $token;
                $userVerification->user_id = $user['id'];
                $userVerification->status = UserVerification::STATUS_PENDING;
                $userVerification->save();

                $msg = 'Your Verification code is ' . $token;
                $isMessageSent = SMSSender::send($user->phone, $msg);

                if (!$isMessageSent) {
                    return $this->formatResponse('error', 'invalid-number', "Sms was not sent", 400);
                }

                $sentThrough = 'SMS';

                return $this->formatResponse('success', 'sent', ['sent_through' => $sentThrough]);
            }

            return $this->formatResponse('error', 'invalid-phone-number', null, 400);
        }
    }

    // verify OPT
    public function verifyOtp(Request $request){

        // validate phone is submitted
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
        ]);

        if ($validate->fails()) {
                return $this->formatResponse('error', 'required-fields', null, 400);
            }

        $user = User::where('phone', $request->phone)->first();

         if ($user->is_blocked){
             return $this->formatResponse('error', 'user-is-blocked');
         }

        $otpValidation = UserVerification::where('phone', $request->phone)
            ->whereNotNull('token')
            ->where('token', $request->otp)
            ->first();

         if ($otpValidation && $otpValidation->status === UserVerification::STATUS_PENDING) {

            $otpValidation->user_id = $user->id;
            $otpValidation->token = null;
            $otpValidation->status = UserVerification::STATUS_VERIFIED;
            $otpValidation->save();

            // verified user
            $user->update(['phone_verified_at' => now()]);

            // If user is Talent then update the status
            if ($user->role_id === User::ROLE_TALENT){
                $user->talentDetail()->update(['status' => TalentDetails::ACTIVE]);
            }

        }
        else {
            return $this->formatResponse('error', 'invalid-otp', null, 400);
        }

        // login user (create token)
        $user->token = $user->createToken('mirrors-app')->plainTextToken;

        // if user is talent show talent details
        if ($user && $user['role_id'] == User::ROLE_TALENT){
            $user->talent_details = TalentDetails::where('user_id', $user->id)->first();
        }

        return $this->formatResponse('success', 'authenticated', $user);

    }

    // Login
    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validator->errors()->first(),
                400
            );
        }

        $credential['email'] = $request->email;
        $credential['password'] = $request->password;

        $user = User::where("email", $credential['email'])->first();

        if (!$user) {
            return $this->formatResponse(
                'error',
                'incorrect-credentials',
                null,
                404
            );
        }

        if ($user->is_blocked){
            return $this->formatResponse('error', 'user-is-blocked', null,400);
        }

        if (!$user->phone_verified_at) {

        $otpValidation = UserVerification::where('phone', $user->phone)->first();

        if ($otpValidation){

            $token = rand(1000, 9999);
            $otpValidation->update(['token' => $token,'status' => UserVerification::STATUS_PENDING]);

            $msg = 'Your Verification code is ' . $token;
            $isMessageSent = SMSSender::send($user->phone, $msg);
            if (!$isMessageSent) {
                return $this->formatResponse('error', 'invalid-number', "Sms was not sent", 400);
            }

        }
        else {
            return $this->formatResponse('error', 'invalid-number', null, 400);
        }
            return $this->formatResponse(
                'error',
                'user-not-verified',
                $user,
                400
            );

        }

        if (!Hash::check($credential['password'], $user->password)) {
            return $this->formatResponse(
                'error',
                'incorrect-credentials',
                null,
                400
            );
        }

        if (Auth::attempt($credential)) {

            $user = Auth::user();
            $user->token = $user->createToken('mirrors-app')->plainTextToken;
            if ($user['role_id'] == User::ROLE_TALENT){
                $user->talent_detail  = TalentDetails::where('user_id', $user['id'])->first();
            }

            return $this->formatResponse(
                'success',
                'login-successfully',
                $user
            );
        }

        return $this->formatResponse(
            'error',
            'incorrect-credentials',
            null,
            400
        );
    }

    // Logout
    public function logOut()
    {
        Auth::user()->token()->revoke();
        Auth::user()->token()->delete();
        return $this->formatResponse('success', 'logout successfully!');
    }

    // social login and signUp
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'provider_id' => 'required',
            'provider' => 'required|in:apple,google', // apple/google
            'name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validator->errors()->first()
            );
        }

        if (strtolower($request->provider) == "apple") {
            $user = User::where("provider_id", trim($request->provider_id))->first();
        }
        elseif (strtolower($request->provider) == "google") {
            $user = User::where("google_id", trim($request->provider_id))->first();
        }

        if (is_null($user) && isset($request->email) && trim($request->email)) {

            $user = User::where(function ($query) use ($request){
                $query->where('email', $request['email'])
                    ->orWhere('phone', $request['phone']);
            })->first();
        }

        // if user is blocked
        if ($user && $user->is_blocked){
            return $this->formatResponse('error', 'user-is-blocked', null,400);
        }

        // for signUp
        $isSignup = false;

        if (!$user) {

            if (!$request->email) {
                return $this->formatResponse('error', 'email-not-found', null, 400);
            }

            $isSignup = true;

            $user = new User();
            $user->name = $request->name ?? " ";
            $user->email = $request->email ?? null;
            $user->email_verified_at = null;
            $user->phone = Str::random(10);
            $user->phone_verified_at = null;
            $user->password = Hash::make($request->provider . $request->provider);
            $user->role_id = User::ROLE_USER;
            $user->is_blocked = false;
        }

        $user->provider = $request->provider ?? null;
        $user->is_social = true;

        if (trim(strtolower($request->provider)) == "apple") {
            $user->provider_id = $request->provider_id ?? null;
        }
        elseif (trim(strtolower($request->provider)) == "google") {
            $user->google_id = $request->provider_id ?? null;
        }

        $user->save();
        $user->is_signup = $isSignup;

        // if user has account but not verified
        if ($isSignup === false && $user && is_null($user->phone_verified_at)){

            $user->is_signup = true;
            return $this->formatResponse(
                'success',
                'social-login-successfully',
                $user
            );
        }

        // if user has account and verified
        if ($isSignup === false){
            $user->token = $user->createToken('mirrors-app')->plainTextToken;
        }

        return $this->formatResponse(
            'success',
            'social-login-successfully',
            $user
        );
    }

    public function socialLoginPhoneVerification(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->formatResponse(
                'error',
                'validation-error',
                $validator->errors()->first()
            );
        }

        $user = User::where(function ($query) use ($request){
            $query->where('email', $request['email'])
                ->orWhere('phone', $request['phone']);
        })
            ->first();

        if ($user){

            // if user email not match
            if ($user->email != $request->email){
                return $this->formatResponse('error', 'this-phone-number-associated-with-other-user', null, 403);
            }

            if ($user->is_blocked){
                return $this->formatResponse('error', 'user-is-blocked',null, 403);
            }

            if ($user->phone_verified_at){
                return $this->formatResponse('error', 'this-user-is-already-verified',null, 403);
            }

            if ($user->is_social == 0){

                return $this->formatResponse(
                    'error',
                    'social-sign-up-is-required',
                    null,
                    400
                );

            }

            if ((is_null($user->provider_id) || is_null($user->provider) || $user->provider != 'apple') && (is_null($user->google_id) || is_null($user->provider) || $user->provider != 'google')){

                return $this->formatResponse(
                    'error',
                    'social-provider-is-missing',
                    null,
                    400
                );

            }

            // get country from phone number
            $country = (new \App\Models\Country)->getCountryFromPhone($request->phone);

            // if phone number not belongs to any country
            if (!$country){
                return $this->formatResponse('error', 'country-not-found', null, 400);
            }

            $otpValidation = UserVerification::where('phone', $request->phone)
                ->first();

            if ($otpValidation){

                $token = rand(1000, 9999);

                $otpValidation->update([
                    'token' => $token,
                    'status' => UserVerification::STATUS_PENDING
                ]);

                $user->update([
                    'phone' => $request['phone'],
                    'country_id' => $country['id'],
                ]);

                $msg = 'Your Verification code is ' . $token;
                $isMessageSent = SMSSender::send($user->phone, $msg);

                if (!$isMessageSent) {
                        return $this->formatResponse('error', 'invalid-number', "Sms was not sent", 400);
                    }

                $sentThrough = 'SMS';

                return $this->formatResponse('success', 'sent', ['sent_through' => $sentThrough]);

            }
            else{

                UserVerification::where('email', $request->email)->delete();
                // create new Verification
                $token = rand(1000, 9999);

                $userVerification = new UserVerification();
                $userVerification->email = $request['email'];
                $userVerification->country_id = $country['id'];
                $userVerification->phone = $request['phone'];
                $userVerification->token = $token;
                $userVerification->user_id = $user['id'];
                $userVerification->status = UserVerification::STATUS_PENDING;
                $userVerification->save();

                $user->update([
                    'phone' => $request['phone'],
                    'country_id' => $country['id'],
                ]);

                $msg = 'Your Verification code is ' . $token;
                $isMessageSent = SMSSender::send($user->phone, $msg);

                if (!$isMessageSent) {
                    return $this->formatResponse('error', 'invalid-number', "Sms was not sent", 400);
                }

                $sentThrough = 'SMS';

                return $this->formatResponse('success', 'sent', ['sent_through' => $sentThrough]);
            }
        }

        return $this->formatResponse('error', 'invalid-phone-number', null, 400);

    }
}
