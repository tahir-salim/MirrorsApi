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

trait TalentTrait
{

    public function appendExtraParams($talent = null)
    {
        if ($talent) {

            // Cache::forget(User::TALENT_CACHE);
            return Cache::remember(User::TALENT_CACHE . $talent->id, now()->addSeconds(1), function () use ($talent) {
                $categories = collect(Category::all());
                $talent->categories = $categories->where('is_active', true)->values()->toArray();
                // $talent->new_categories = $categories->sortByDesc('created_at')->take(5);
                $talent->new_talents = User::isNotBlocked()->select(
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
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                $talent->featured_categories = $categories->where('is_featured', true)->where('is_active', true)->values()->toArray();
                $talent->settings = Settings::showForTalent()->get();
                $talent->notifications = Notification::unReadNotification()->get();
                $talent->completed_request_reviews = BookingRequest::isCompleted()
                    ->with(['review'])
                    ->where('talent_user_id', $talent->id)
                    ->get();
                return $talent;
            });

        }
    }

    public function trendingCategories()
    {
        // Cache::forget(User::TALENT_CATEGORIES_CACHE);
        return Cache::remember(User::TALENT_CATEGORIES_CACHE, now()->addDay(), function () {

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
}
