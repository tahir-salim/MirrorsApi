<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         $services = [
           [
               'service_category_id' => 1,
               'name' => 'Instagram Picture Post',
               'description' => 'A normal Instagram Picture Post',
               'icon' => '-',
               'created_at' => now(),
               'updated_at' => now()
           ],
           [
               'service_category_id' => 1,
               'name' => 'Instagram IGTV',
               'description' => 'A normal Instagram IGTV',
               'icon' => '-',
               'created_at' => now(),
               'updated_at' => now()
           ],
           [
               'service_category_id' => 2,
               'name' => 'Snapchat Story',
               'description' => 'A normal Snapchat Story',
               'icon' => '-',
               'created_at' => now(),
               'updated_at' => now()
           ],
           [
               'service_category_id' => 3,
               'name' => 'Tiktok Video',
               'description' => 'A normal Tiktok Video',
               'icon' => '-',
               'created_at' => now(),
               'updated_at' => now()
           ],
           [
               'service_category_id' => 4,
               'name' => 'Youtube Video',
               'description' => 'A normal youtube Video',
               'icon' => '-',
               'created_at' => now(),
               'updated_at' => now()
           ],
           [
               'service_category_id' => 4,
               'name' => 'Youtube Stream',
               'description' => 'A normal Youtube Stream',
               'icon' => '-',
               'created_at' => now(),
               'updated_at' => now()
           ]
        ];
        DB::table('services')->insert($services);
    }
}
