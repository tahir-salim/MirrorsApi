<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\BookingRequest;
class BookingRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $booking_reqeusts = [
            [
                'user_id' => 4,
                'talent_user_id' => 2,
                'price' => 20,
                'status' => BookingRequest::COMPLETED,
                'requested_delivery_date' => now()->format('y-m-d'),
                'time' => '1:00 PM',
                'duration' => '2:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 4,
                'talent_user_id' => 2,
                'price' => 50,
                'status' => BookingRequest::COMPLETED,
                'requested_delivery_date' => now()->format('y-m-d'),
                'time' => '6:00 PM',
                'duration' => '1:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 4,
                'talent_user_id' => 2,
                'price' => 100,
                'status' => BookingRequest::COMPLETED,
                'requested_delivery_date' => now()->format('y-m-d'),
                'time' => '2:00 PM',
                'duration' => '1:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 4,
                'talent_user_id' => 2,
                'price' => 100,
                'status' => BookingRequest::PENDING,
                'requested_delivery_date' => now()->format('y-m-d'),
                'time' => '3:00 PM',
                'duration' => '1:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 4,
                'talent_user_id' => 2,
                'price' => 50,
                'status' => BookingRequest::CANCELED,
                'requested_delivery_date' => now()->format('y-m-d'),
                'time' => '5:00 PM',
                'duration' => '1:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 4,
                'talent_user_id' => 2,
                'price' => 90,
                'status' => BookingRequest::REJECTED,
                'requested_delivery_date' => now()->format('y-m-d'),
                'time' => '4:00 PM',
                'duration' => '1:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 4,
                'talent_user_id' => 2,
                'price' => 90,
                'status' => BookingRequest::ACCEPTED,
                'requested_delivery_date' => now()->format('y-m-d'),
                'time' => '3:00 PM',
                'duration' => '1:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

     DB::table('booking_requests')->insert($booking_reqeusts);

    }
}
