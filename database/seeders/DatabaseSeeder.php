<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(NovaUserSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ServiceCategorySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(BookingRequestSeeder::class);
        $this->call(RequestServiceSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(PackageServiceSeeder::class);
        $this->call(RequestPackageSeeder::class);
        $this->call(ServiceUserSeeder::class);
        $this->call(TalentScheduleSeeder::class);
    }
}
