<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentSchedule extends Model
{
    use HasFactory;

    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    protected $table = 'talent_schedule';

    protected $fillable = [
        'user_id',
        'day',
        'time',
    ];

    protected $hidden = [
        'id',
        'user_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function splitTime($startTime, $endTime, $duration = Settings::TIME_DURATION_LIMIT){

        $timeSlotArray = array (); // define output

        $startingTime = strtotime ($startTime); // get Timestamp
        $endingTime   = strtotime ($endTime); // get Timestamp
        $addMin  = $duration * 60;

        while ($startingTime <= $endingTime) // run loop
        {
            $timeSlotArray[] = date ("g:i A", $startingTime);
            $startingTime += $addMin; //ending time check
        }

        return $timeSlotArray;
    }

    // for create time slot action
    public static function todayTimeSlot(){

        $todayTiming = self::splitTime(now()->startOfDay()->format('g:i A'),now()->endOfDay()->format('g:i A'));

        if ($todayTiming){

            $timeSlots = array_values($todayTiming);
            return array_combine($timeSlots, $timeSlots);

        }else{
            return [];
        }

    }

}
