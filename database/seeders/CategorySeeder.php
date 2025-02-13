<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Entertainers',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Public Figures',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Foodies',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Video Gamers',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fashion & Beauty',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Artists',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Health & Fitness',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Musicians',
                'is_active' => true,
                'image_wide' => '-',
                'image_square' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('categories')->insert($categories);
    }
}
