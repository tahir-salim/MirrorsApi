<?php

namespace App\Observers;

use App\Models\TalentDetails;
use App\Models\User;
use App\Models\UserVerification;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user){

        if (strpos(request()->url(),'nova-api/talent') || strpos(request()->url(),'nova-api/users')){
            $country = (new \App\Models\Country)->getCountryFromPhone($user->phone);
            $user->country_id = $country?$country['id']:$user['country_id'];
        }

    }
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // if talent is created from dashboard then create talent detail for that talent
        if (strpos(request()->url(),'nova-api/talent') || strpos(request()->url(),'nova-api/users')){

            if ($user->role_id == User::ROLE_TALENT){

                $talentDetail = TalentDetails::where('user_id', $user['id'])->first();
                if (!$talentDetail){
                    $talentDetail = new TalentDetails();
                    $talentDetail->user_id = $user['id'];
                    $talentDetail->status = TalentDetails::ACTIVE;
                    $talentDetail->save();
                }
            }

            // user verification table for nova created users
            $userVerification = new UserVerification();
            $userVerification->email = $user['email'];
            $userVerification->country_id = $user['country_id'];
            $userVerification->phone = $user['phone'];
            $userVerification->token = null;
            $userVerification->user_id = $user['id'];
            $userVerification->status = UserVerification::STATUS_VERIFIED;
            $userVerification->save();

        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
