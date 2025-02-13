<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Khaled Janahi',
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '+97332221456',
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Maryam Awadh',
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '+96832221456',
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Raed Almutawa',
                'email' => Str::random(10).'@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '+97132221456',
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ];

        DB::table('users')->insert($users);
    }
}
