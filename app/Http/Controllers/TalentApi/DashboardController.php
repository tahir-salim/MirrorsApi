<?php

namespace App\Http\Controllers\TalentApi;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Traits\TalentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use TalentTrait;

    public function init(){

        $talent = User::isNotBlocked()
            ->with([
                'talentRequests' => function($query){
                $query
                    ->with(['review' => function ($q) {
                            $q->where('status', Review::STATUS_APPROVED);
                    }, 'packages', 'services', 'requestAttachments', 'requestComments.user', 'user'])
                    ->latest()
                    ->take(10);
        },
            'services',
            'talentDetail',
            'packages.services',
            'talentPosts',
            'talentSchedules'
        ])
            ->find(Auth::id());

        if (!$talent){
            return $this->formatResponse('error', 'user-not-found', null, 404);
        }

        if ($talent['role_id'] != User::ROLE_TALENT){
            return $this->formatResponse('error', 'unauthorized', null, 404);
        }

        $talent = $this->appendExtraParams($talent);

        $talent->trending_categories  = $this->trendingCategories();

        return $this->formatResponse('success','talent-init', $talent);
    }
}
