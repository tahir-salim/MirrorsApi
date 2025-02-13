<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PackageServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $package_services = [
            [
                'package_id' => 1,
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'package_id' => 1,
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'package_id' => 2,
                'service_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
             [
                'package_id' => 2,
                'service_id' => 6,
                'created_at' => now(),
                'updated_at' => now()
            ]
             ];
    DB::table('package_services')->insert($package_services);
    }
}
