<?php

namespace App\Http\Controllers\TalentApi;

use App\Http\Controllers\Controller;
use App\Models\TalentSchedule;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ScheduleController extends Controller
{
    public function show()
    {
        $schedule = TalentSchedule::select('time', 'day')
                                  ->where('user_id', Auth::id())
                                  ->get()
                                  ->groupBy('day');

        return $this->formatResponse('success', 'talent-schedule', $schedule);
    }
    public function store(Request $request)
    {
          $validation = Validator::make($request->all(), [
            'days' => 'required',
            'days.*.day' => 'required|integer',
            'days.*.timings' => 'nullable|array',
            // 'days.*.timings.*.time' => 'required|date_format:g:i A',
        ]);

        if ($validation->fails()) {
            return $this->formatResponse('error', 'days-required', $validation->errors()->first(), 400);
        }

        // Validate user is a talent
         // check DB For talent exist
        $talent = User::isNotBlocked()->isTalent()->find(Auth::id());

        if (!$talent){
            return $this->formatResponse('error', 'talent-not-found', null, 403);
        }
        foreach($request->days as $daySlot) {
        // Remove values from table for the selected day
        TalentSchedule::where('user_id', Auth::id())
                    ->where('day', $daySlot['day'])
                    ->delete();

         foreach($daySlot['timings'] as $timeslot) {
                TalentSchedule::create([
                    'user_id' => Auth::id(),
                    'day'=> $daySlot['day'],
                    'time' => $timeslot,
                ]);
        }
        }
        $newSchedule  = TalentSchedule::where('user_id', Auth::id())
                                      ->get();
        return $this->formatResponse('success', 'talent-schedule-updated', $newSchedule);
    }
}
