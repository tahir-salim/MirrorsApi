<?php

namespace App\Providers;

use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\RequestCharges;
use App\Models\ServiceUser;
use App\Models\TalentPost;
use App\Models\TalentSchedule;
use App\Models\User;
use App\Observers\BookingRequestObserver;
use App\Observers\PackageObserver;
use App\Observers\RequestChargesObserver;
use App\Observers\ServiceUserObserver;
use App\Observers\TalentPostObserver;
use App\Observers\TalentScheduleObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Package::observe(PackageObserver::class);
        User::observe(UserObserver::class);
        TalentSchedule::observe(TalentScheduleObserver::class);
        BookingRequest::observe(BookingRequestObserver::class);
        ServiceUser::observe(ServiceUserObserver::class);
        TalentPost::observe(TalentPostObserver::class);
        RequestCharges::observe(RequestChargesObserver::class);
    }
}
