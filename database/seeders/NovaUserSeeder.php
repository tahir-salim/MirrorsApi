<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NovaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->email = 'admin@mirrors.com';
        $user->name = 'Admin';
        $user->password = Hash::make('admin@mirrors');
        $user->phone = '-';
        $user->is_blocked = false;
        $user->role_id = User::ROLE_ADMIN;
        $user->is_social = false;
        $user->save();
    }
}
