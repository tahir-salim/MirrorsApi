<?php

namespace App\Observers;

use App\Models\TalentSchedule;

class TalentScheduleObserver
{
    /**
     * Handle the TalentSchedule "creating" event.
     *
     * @param  \App\Models\TalentSchedule  $talentSchedule
     * @return void
     */
    public function creating(TalentSchedule $talentSchedule)
    {
        //
    }

    /**
     * Handle the TalentSchedule "created" event.
     *
     * @param  \App\Models\TalentSchedule  $talentSchedule
     * @return void
     */
    public function created(TalentSchedule $talentSchedule)
    {
        //
    }

    /**
     * Handle the TalentSchedule "updated" event.
     *
     * @param  \App\Models\TalentSchedule  $talentSchedule
     * @return void
     */
    public function updated(TalentSchedule $talentSchedule)
    {
        //
    }

    /**
     * Handle the TalentSchedule "deleted" event.
     *
     * @param  \App\Models\TalentSchedule  $talentSchedule
     * @return void
     */
    public function deleted(TalentSchedule $talentSchedule)
    {
        //
    }

    /**
     * Handle the TalentSchedule "restored" event.
     *
     * @param  \App\Models\TalentSchedule  $talentSchedule
     * @return void
     */
    public function restored(TalentSchedule $talentSchedule)
    {
        //
    }

    /**
     * Handle the TalentSchedule "force deleted" event.
     *
     * @param  \App\Models\TalentSchedule  $talentSchedule
     * @return void
     */
    public function forceDeleted(TalentSchedule $talentSchedule)
    {
        //
    }
}
