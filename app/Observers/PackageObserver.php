<?php

namespace App\Observers;

use App\Models\Package;

class PackageObserver
{
    /**
     * Handle the Package "created" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function created(Package $package)
    {
        //
    }

    /**
     * Handle the Package "updated" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function updated(Package $package)
    {
        //
    }
    /**
     * Handle the Package "deleting" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function deleting(Package $package){
        //
    }
    /**
     * Handle the Package "deleted" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function deleted(Package $package)
    {
        // $package->bookingRequest()->detach();
        // $package->services()->detach();
    }

    /**
     * Handle the Package "restored" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function restored(Package $package)
    {
        //
    }

    /**
     * Handle the Package "force deleted" event.
     *
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function forceDeleted(Package $package)
    {
        //
    }
}
