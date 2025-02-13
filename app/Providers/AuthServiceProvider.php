<?php

namespace App\Providers;

use App\Models\BookingRequest;
use App\Models\Category;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Country;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Package;
use App\Models\PackageService;
use App\Models\RequestCharges;
use App\Models\Review;
use App\Models\Service;
use App\Models\TalentSchedule;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserVerification;
use App\Policies\BookingRequestPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ChatPolicy;
use App\Policies\ChatUserPolicy;
use App\Policies\CountryPolicy;
use App\Policies\MessagePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PackagePolicy;
use App\Policies\PackageServicePolicy;
use App\Policies\RequestChargesPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\ServicePolicy;
use App\Policies\TalentSchedulePolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserVerificationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        UserVerification::class => UserVerificationPolicy::class,
        Package::class => PackagePolicy::class,
        Service::class => ServicePolicy::class,
        TalentSchedule::class => TalentSchedulePolicy::class,
        Notification::class => NotificationPolicy::class,
        Country::class => CountryPolicy::class,
        Review::class => ReviewPolicy::class,
        Category::class => CategoryPolicy::class,
        PackageService::class => PackageServicePolicy::class,
        Chat::class => ChatPolicy::class,
        ChatUser::class => ChatUserPolicy::class,
        Message::class => MessagePolicy::class,
        RequestCharges::class => RequestChargesPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
