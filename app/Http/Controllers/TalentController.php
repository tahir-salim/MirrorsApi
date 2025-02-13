<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Review;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TalentController extends Controller
{
    public function showTalent($talentID)
    {

        $talent = User::isNotBlocked()
            ->whereHas('talentDetail')
            ->with([
                'talentDetail',
                'packages.services',
                'talentRequests' => function ($bookingRequest) {
                    $bookingRequest
                        ->with([
                            'review' => function ($q) {
                                $q->where('status', Review::STATUS_APPROVED);
                            },
                            'requestAttachments',
                            'user',
                            'services',
                            'packages',
                        ])
                        ->has('review');
                },
                'talentPosts',
            ])
            ->isTalent()
            ->find($talentID);

        if (!$talent) {
            return $this->formatResponse('error', 'talent-not-found', null, 404);
        }

        $talent->service_categories = ServiceCategory::whereHas('services', function ($q) use ($talent) {
            $q->whereHas('serviceUsers', function ($query) use ($talent) {
                $query
                    ->where('is_active', true)
                    ->where('user_id', $talent->id);
            });
        })
            ->with(['services' => function ($services) use ($talent) {
                $services
                    ->whereHas('serviceUsers', function ($q) use ($talent) {
                        $q->where('is_active', true)
                            ->where('user_id', $talent->id);
                    })
                    ->with(['serviceUser' => function ($q) use ($talent) {
                        $q
                            ->where('is_active', true)
                            ->where('user_id', $talent->id);
                    }]);
            }])
            ->get();

        return $this->formatResponse('success', 'get-talent-detail', $talent);
    }

    public function talentsList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,trending',
            'search' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validator->errors()->first(),
                400
            );
        }

        $limit = 10;
        $search = $request->search;

        $talents = User::isNotBlocked()
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
            )
            ->where('role_id', User::ROLE_TALENT)
            ->has('talentSchedules')
            ->whereHas('talentDetail');

        if ($request->status == 'new') {

            $newTalents = $talents->orderBy('created_at', 'desc')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->paginate($limit);
            return $this->formatResponse('success', 'get-new-talents-successfully', $newTalents);
        }

        if ($request->status == 'trending') {

            $trendingTalentIds = BookingRequest::whereBetween('created_at', [today()->subWeeks(2)->toDateString(), today()->toDateString()])
                ->whereIn('status', [BookingRequest::PENDING, BookingRequest::COMPLETED, BookingRequest::ACCEPTED])
                ->select('talent_user_id', DB::raw('COUNT(talent_user_id) as talent_count'))
                ->groupBy('talent_user_id')
                ->orderByRaw('talent_count DESC')
                ->pluck('talent_user_id');

            $trendingTalent = $talents->whereIn('id', $trendingTalentIds)
                ->orderByRaw('FIELD(id,' . $trendingTalentIds->implode(',') . ')')
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })
                ->paginate($limit);

            return $this->formatResponse('success', 'get-trending-talents-successfully', $trendingTalent);
        }
    }
}
