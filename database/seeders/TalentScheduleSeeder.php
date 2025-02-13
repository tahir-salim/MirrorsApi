<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalentScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $talentSchedule = [
            [
                'user_id' => 1,
                'day' => 0,
                'time' => '6:00 PM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'day' => 0,
                'time' => '6:30 PM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'day' => 0,
                'time' => '7:00 PM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'day' => 1,
                'time' => '6:00 PM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'day' => 1,
                'time' => '7:30 PM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'day' => 1,
                'time' => '8:00 PM',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('talent_schedule')->insert($talentSchedule);
    }
}
