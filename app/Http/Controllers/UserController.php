<?php

namespace App\Http\Controllers;

use App\Models\TalentDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateUser(Request $request){

        $user = Auth::user();
        if (!($user['role_id'] == User::ROLE_USER)){

            return $this->formatResponse(
                'error',
                'user-not-found',
                null,
                403
            );
        }

        $authUser = $request->only([
            'name',
            'email',
            'country_id',
            'phone',
            'role_id',
            'last_ip_address',
            'last_activity',
            'device_os',
            'device_os_version',
            'device_token',
            'device_name',
            'app_version'
        ]);

        $authUser['last_ip_address'] = \request()->ip();
        $authUser['last_activity'] = now();

        if (isset($request->current_password) && isset($request->new_password)){

            if (!Hash::check($request->current_password, $user['password'])) {
                return $this->formatResponse(
                    'error',
                    'password-invalid',
                    null,
                    400
                );
            }

            $authUser['password'] = Hash::make($request->new_password);
        }

        $user->update($authUser);
        return $this->formatResponse('success', 'profile-edit-success-fully', Auth::user());

    }

    public function updateTalent(Request $request){

        $user = Auth::user();

        if (!($user['role_id'] == User::ROLE_TALENT)){

            return $this->formatResponse(
                'error',
                'user-not-found'
            );
        }

        $authUser = $request->only([
            'name',
            'email',
            'country_id',
            'phone',
            'role_id',
            'last_ip_address',
            'last_activity',
            'device_token',
            'device_os',
            'device_os_version',
            'device_name',
            'app_version'
        ]);

        $authUser['last_ip_address'] = \request()->ip();
        $authUser['last_activity'] = now();

        TalentDetails::where('user_id', Auth::id())
            ->update($request->only([
                'title',
                'about',
                'social_instagram',
                'social_snapchat',
                'social_youtube',
                'social_twitter',
                'social_tik_tok',
                'is_featured',
                'bank_name',
                'bank_account_owner',
                'bank_iban',
                'location'
                ]));

        if (isset($request->current_password) && isset($request->new_password)){

            if (!Hash::check($request->current_password, $user['password'])) {
                return $this->formatResponse(
                    'error',
                    'password-invalid',
                    null,
                    400
                );
            }

            $authUser['password'] = Hash::make($request->new_password);
        }

        $user->update($authUser);

        return $this->formatResponse('success', 'profile-edit-success-fully', Auth::user()->loadMissing(['talentDetail']));

    }

    public function resetPassword(Request $request){

        $validation = Validator::make($request->all(), [
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|min:6',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'validation-error', $validation->errors()->first(), 400);
        }

        $user = Auth::user();

        if (!$user){
            return $this->formatResponse('error', 'unauthorized', null, 403);
        }

        if (is_null($user['phone_verified_at'])){
            return $this->formatResponse('error', 'user-not-verified', null, 403);
        }

        if ($request->new_password !=  $request->confirm_new_password){
            return $this->formatResponse('error', 'password-not-match', null, 403);
        }

        $user['password'] = Hash::make($request->new_password);
        $user->save();

        return $this->formatResponse('success', 'password-change-successfully');
    }

    public function uploadProfileImage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors(),
                400
            );
        }

        $user = Auth::user();

        if (!$user){
            return $this->formatResponse('error', 'unauthorized', 403);
        }

        $filePath = $request->file('file')->store('images/users', 's3');
        $user->update(['avatar' => $filePath]);

        return $this->formatResponse('success', 'successfully-uploaded', $user);
    }

    public function updateCategories(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'categoriesIds' => 'required|array',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors(),
                400
            );
        }

        $user = Auth::user();

        if($user)
        {
            $user->categories()->sync($request->categoriesIds);
        }

        return $this->formatResponse('success', 'update-categories-successfully', $user);
    }
}
