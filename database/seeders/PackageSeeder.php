<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            [
                'user_id' => 2,
                'name' => 'Instagram Full Coverage',
                'price' => 50,
                'description' => 'Provide a full coverage of all instagram features',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 2,
                'name' => 'Youtube Video + Stream',
                'price' => 100,
                'description' => 'Film a video and stream for one hour',
                'created_at' => now(),
                'updated_at' => now()
            ]
            ];
    DB::table('packages')->insert($packages);
    }
}
