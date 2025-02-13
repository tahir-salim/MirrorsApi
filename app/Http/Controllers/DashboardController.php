<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use UserTrait;

    public function init()
    {

        $user = User::isNotBlocked()
            ->with(['requests' => function ($query) {
                $query
                    ->with(['talentUser', 'review', 'packages', 'services', 'requestAttachments', 'requestComments.user', 'requestCharges'])
                    ->latest()
                    ->take(10);
            }])->find(Auth::id());

        if (!$user) {
            return $this->formatResponse('error', 'user-not-found', null, 404);
        }

        if ($user['role_id'] != User::ROLE_USER) {
            return $this->formatResponse('error', 'unauthorized', null, 404);
        }

        $user = $this->appendExtraParams($user);

        $user->trending_categories = $this->trendingCategories();
        $user->trending_talents = $this->trendingTalents();

        return $this->formatResponse('success', 'user-init', $user);
    }
}
