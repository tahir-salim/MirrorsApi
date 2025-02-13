<?php
namespace App\Traits;

use App\Models\BookingRequest;
use App\Models\Category;
use App\Models\CategoryUser;
use App\Models\Notification;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait UserTrait
{

    public function appendExtraParams($user = null)
    {
        if ($user) {

            // Cache::forget(User::USER_CACHE);
            return Cache::remember(User::USER_CACHE . $user->id, now()->addSeconds(1), function () use ($user) {
                $categories = collect(Category::all());
                $user->categories = $categories->where('is_active', true)->values()->toArray();
                $user->new_categories = $categories->sortByDesc('created_at')->take(5)->values()->toArray();
                $user->new_talents = $this->newTalents();
                $user->featured_categories = $categories->where('is_featured', true)->where('is_active', true)->values()->toArray();
                $user->selected_categories = $user->categories()->get();
                $user->settings = Settings::where('show_for_user', true)->get();
                $user->notifications = Notification::where('user_id', $user->id)->unReadNotification()->get();
                return $user;
            });

        }
    }

    public function trendingCategories()
    {

        // Cache::forget(User::USER_TRENDING_CACHE);
        return Cache::remember(User::USER_TRENDING_CACHE, now()->addDay(), function () {

            $talentIds = BookingRequest::whereBetween('created_at', [today()->subWeeks(2)->toDateString(), today()->toDateString()])
                ->whereIn('status', [BookingRequest::PENDING, BookingRequest::COMPLETED, BookingRequest::ACCEPTED])
                ->select('talent_user_id')
                ->groupBy('talent_user_id')
                ->pluck('talent_user_id');

            $categories = [];

            if (count($talentIds) > 0) {

                $categories = CategoryUser::whereIn('user_id', $talentIds)
                    ->select('category_id', DB::raw('COUNT(category_id) as category_count'))
                    ->groupBy('category_id')
                    ->orderByRaw('category_count DESC')
                    ->take(5)
                    ->get()
                    ->map(function ($category) {
                        return [
                            'id' => $category->category_id,
                            'name' => $category->category->name,
                            'is_active' => $category->category->is_active,
                            'image_wide' => $category->category->image_wide,
                            'image_square' => $category->category->image_square,
                            'count' => $category->category_count,
                        ];
                    });
            }

            return $categories;
        });
    }

    public function trendingTalents()
    {

        // Cache::forget(User::TALENT_TRENDING_CACHE);
        return Cache::remember(User::TALENT_TRENDING_CACHE, now()->addDay(), function () {

            $trendingTalents = BookingRequest::whereBetween('created_at', [today()->subWeeks(2)->toDateString(), today()->toDateString()])
                ->whereIn('status', [BookingRequest::PENDING, BookingRequest::COMPLETED, BookingRequest::ACCEPTED])
                ->select('talent_user_id', DB::raw('COUNT(talent_user_id) as talent_count'))
                ->groupBy('talent_user_id')
                ->orderByRaw('talent_count DESC')
                ->with('talentUser', function ($query) {
                    $query->isNotBlocked()
                        ->whereHas('talentDetail')
                        ->has('talentSchedules')
                        ->select(
                            'id',
                            'name',
                            'email',
                            'country_id',
                            'phone',
                            'avatar',
                            'branch_origin_id',
                            'branch_origin',
                            'is_social',
                            'last_activity',
                            'is_blocked',
                            'created_at'
                        );
                })
                ->take(5)
                ->get()
                ->pluck('talentUser');

            return $trendingTalents;
        });
    }

    public function newTalents()
    {
        $newTalents = User::isNotBlocked()->select(
            'id',
            'name',
            'email',
            'country_id',
            'phone',
            'avatar',
            'branch_origin_id',
            'branch_origin',
            'is_social',
            'last_activity',
            'is_blocked',
            'created_at'
        )
            ->where('role_id', User::ROLE_TALENT)
            ->has('talentSchedules')
            ->whereHas('talentDetail')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return $newTalents;
    }

}
