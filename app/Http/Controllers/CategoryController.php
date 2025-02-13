<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use UserTrait;

    public function index()
    {

        $categories = Category::all();

        $active_categories = $categories
            ->where('is_active', true)
            ->values();

        $new_categories = $active_categories
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        $featured_categories = $active_categories
            ->where('is_featured', true)
            ->take(10)
            ->values();

        $new_talents = $this->newTalents();

        $trending_categories = $this->trendingCategories();
        $trending_talents = $this->trendingTalents();

        return $this->formatResponse('success', 'get-all-active-categories', [
            'all_active_categories' => $active_categories,
            'new_categories' => $new_categories,
            'featured_categories' => $featured_categories,
            'trending_categories' => $trending_categories,
            'trending_talents' => $trending_talents,
            'new_talents' => $new_talents,
        ]);
    }

    public function showTalents(Request $request, $categoryID)
    {

        $category = Category::find($categoryID);

        if (!$category) {
            return $this->formatResponse('error', 'category-is-not-active', null, 404);
        }

        $talents = User::isNotBlocked()
            ->isTalent()
            ->has('talentSchedules')
            ->with(['talentSchedules'])
            ->whereHas('talentDetail')
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            });

        if ($request->talentName) {
            $talents->where('name', 'like', "%$request->talentName%");
        }
        return $talents->paginate(20);
        return $this->formatResponse('success', 'get-list-of-talents', $talents);
    }

    public function searchResults(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->where('name', 'like', "%$request->text%")
            ->get();

        $talents = User::isNotBlocked()
            ->isTalent()
            ->has('talentSchedules')
            ->with(['talentSchedules'])
            ->whereHas('talentDetail')
            ->where('name', 'like', "%$request->text%")
            ->get();

        $results = [
            'categories' => $categories,
            'talents' => $talents,
        ];

        return $this->formatResponse('success', 'search-results', $results);
    }
}
