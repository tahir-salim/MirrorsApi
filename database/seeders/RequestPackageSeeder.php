<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requestPackages = [
            [
                'booking_request_id' => 1,
                'package_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'booking_request_id' => 2,
                'package_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'booking_request_id' => 3,
                'package_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ];
    DB::table('request_packages')->insert($requestPackages);
    }
}
