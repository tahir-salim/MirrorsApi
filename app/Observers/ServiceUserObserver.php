<?php

namespace App\Observers;

use App\Models\ServiceUser;

class ServiceUserObserver
{
    /**
     * Handle the ServiceUser "created" event.
     *
     * @param  \App\Models\ServiceUser  $serviceUser
     * @return void
     */
    public function created(ServiceUser $serviceUser)
    {
        //
    }

    /**
     * Handle the ServiceUser "updated" event.
     *
     * @param  \App\Models\ServiceUser  $serviceUser
     * @return void
     */
    public function updated(ServiceUser $serviceUser)
    {
        //
    }

    /**
     * Handle the ServiceUser "deleted" event.
     *
     * @param  \App\Models\ServiceUser  $serviceUser
     * @return void
     */
    public function deleted(ServiceUser $serviceUser)
    {
        //
    }

    /**
     * Handle the ServiceUser "restored" event.
     *
     * @param  \App\Models\ServiceUser  $serviceUser
     * @return void
     */
    public function restored(ServiceUser $serviceUser)
    {
        //
    }

    /**
     * Handle the ServiceUser "force deleted" event.
     *
     * @param  \App\Models\ServiceUser  $serviceUser
     * @return void
     */
    public function forceDeleted(ServiceUser $serviceUser)
    {
        //
    }
}
