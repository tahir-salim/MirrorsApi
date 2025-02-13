<?php

namespace App\Traits;

use App\Models\TalentSchedule;
use App\Models\User;

trait BookingRequestTrait
{
      public function valdiateTalent($data)
    {
        $talent = User::isNotBlocked()->where('id', $data['talent_id'])->first();

        if (!$talent || $talent->role_id != User::ROLE_TALENT) {
            return false;
        }

        return $talent;
    }

    public function validateTime($data, $talentId)
    {
        $schedule = TalentSchedule::where('user_id', $talentId)
                                  ->where('day', date('w', strtotime($data['requested_delivery_date'])))
                                  ->get();

        if ($schedule->isEmpty()) {
            return false;
        }

        $result = $schedule->where('time', $data['time'])->first();
        if (!$result) {
            return false;
        }

        return $schedule;
    }

    public function validateDuration($data, $schedule)
    {
        $result = $schedule->where('time', $data['time'])->first();
        $time = explode(':', $data['duration']);
        $minutes = ($time[0] * 60.0 + $time[1] * 1.0);

        for ($i = 30; $i < $minutes; $i += 30) {
            $duration_time = date('g:i A', strtotime($result['time']) + ($i * 60)); //Get time(data time + (1 hours * duration))
            $duration_time_check = $schedule->where('time', $duration_time)->first(); //Check result time
            if (!$duration_time_check) { //If result time not in the schedule return error
                return false;
            }
        }
        return true;
    }
}
