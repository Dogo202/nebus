<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Building;
use App\Models\Activity;
use App\Models\Phone;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        // Получаем здания и виды деятельности
        $building1 = Building::find(1);
        $building2 = Building::find(2);
        $building3 = Building::find(3);
        $building4 = Building::find(4);
        $building5 = Building::find(5);

        $food = Activity::where('name', 'Еда')->first();
        $meatProducts = Activity::where('name', 'Мясная продукция')->first();
        $dairyProducts = Activity::where('name', 'Молочная продукция')->first();
        $automobiles = Activity::where('name', 'Автомобили')->first();
        $heavyVehicles = Activity::where('name', 'Грузовые')->first();
        $lightVehicles = Activity::where('name', 'Легковые')->first();
        $spareParts = Activity::where('name', 'Запчасти')->first();
        $accessories = Activity::where('name', 'Аксессуары')->first();

        // Создание организаций
        $org1 = Organization::create([
            'name' => 'ООО "Рога и Копыта"',
            'building_id' => $building1->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $org1->activities()->attach([$food->id, $meatProducts->id]);
        $org1->phones()->createMany([
            ['phone_number' => '2-222-222'],
            ['phone_number' => '3-333-333'],
        ]);

        $org2 = Organization::create([
            'name' => 'ООО "Молочный Рай"',
            'building_id' => $building2->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $org2->activities()->attach([$food->id, $dairyProducts->id]);
        $org2->phones()->createMany([
            ['phone_number' => '4-444-444'],
        ]);

        $org3 = Organization::create([
            'name' => 'ООО "Автозапчасти"',
            'building_id' => $building3->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $org3->activities()->attach([$automobiles->id, $lightVehicles->id, $spareParts->id]);
        $org3->phones()->createMany([
            ['phone_number' => '5-555-555'],
            ['phone_number' => '6-666-666'],
        ]);

        $org4 = Organization::create([
            'name' => 'ООО "Грузовые машины"',
            'building_id' => $building4->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $org4->activities()->attach([$automobiles->id, $heavyVehicles->id]);
        $org4->phones()->createMany([
            ['phone_number' => '7-777-777'],
        ]);

        $org5 = Organization::create([
            'name' => 'ООО "Автоаксессуары"',
            'building_id' => $building5->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $org5->activities()->attach([$automobiles->id, $lightVehicles->id, $accessories->id]);
        $org5->phones()->createMany([
            ['phone_number' => '8-888-888'],
        ]);
    }
}

