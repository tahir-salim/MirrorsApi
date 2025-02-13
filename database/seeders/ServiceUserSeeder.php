<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $serviceUsers = [
            [
                'service_id' => 1,
                'user_id' => 2,
                'is_active' => 1,
                'price' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_id' => 2,
                'user_id' => 2,
                'is_active' => 1,
                'price' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_id' => 5,
                'user_id' => 2,
                'is_active' => 1,
                'price' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_id' => 6,
                'user_id' => 2,
                'is_active' => 1,
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_id' => 2,
                'user_id' => 3,
                'is_active' => 1,
                'price' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ];
    DB::table('service_users')->insert($serviceUsers);
    }
}
