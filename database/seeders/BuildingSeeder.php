<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        Building::insert([
            ['address' => 'г. Москва, ул. Ленина 1, офис 3', 'latitude' => 55.7558, 'longitude' => 37.6176, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'г. Санкт-Петербург, ул. Невский 10, офис 5', 'latitude' => 55.7512, 'longitude' => 37.6183, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'г. Екатеринбург, ул. Вайнера 14, офис 2', 'latitude' => 55.7583, 'longitude' => 37.6205, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'г. Москва, ул. Пушкина 36, офис 3', 'latitude' => 55.7608, 'longitude' => 37.6123, 'created_at' => now(), 'updated_at' => now()],
            ['address' => 'г. Екатеринбург, ул. Ельцина 21, офис 245', 'latitude' => 55.7599, 'longitude' => 37.6148, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
