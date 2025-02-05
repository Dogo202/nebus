<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            BuildingSeeder::class,
            ActivitySeeder::class,
            OrganizationSeeder::class,
            OrganizationActivitySeeder::class,
        ]);
    }
}
