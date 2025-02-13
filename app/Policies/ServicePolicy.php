<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\PackageService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Service $service)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Service $service)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Service $service)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Service $service)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Service $service)
    {
        return false;
    }

    public function attachBookingRequest(User $user, Service $service){
        return false;
    }

    public function attachAnyBookingRequest(User $user, Service $service){
        return false;
    }

    public function attachPackage(User $user, Service $service){
        return false;
    }

    public function attachAnyPackage(User $user, Service $service){
        return false;
    }

}
