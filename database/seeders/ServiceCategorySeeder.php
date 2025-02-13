<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $serviceCategories = [
            [
                'name' => 'Instagram',
                'icon' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Snapchat',
                'icon' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tik Tok',
                'icon' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Youtube',
                'icon' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ]
            ];

            DB::table('service_categories')->insert($serviceCategories);
    }
}
