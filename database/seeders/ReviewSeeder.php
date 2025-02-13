<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reviews = [
            [
                'booking_request_id' => 1,
                'rating' => 5,
                'details' => 'The influencer was very talented and I enjoyed working with him!',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'booking_request_id' => 2,
                'rating' => 3,
                'details' => 'The work was average at best and I did not see any improvement in the business',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'booking_request_id' => 3,
                'rating' => 5,
                'details' => 'Thank you so much! I look forward to working again with you',
                'created_at' => now(),
                'updated_at' => now()
            ]
            ];

        DB::table('reviews')->insert($reviews);
    }
}
