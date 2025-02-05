<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $rootActivities = [
            ['name' => 'Еда', 'parent_id' => null],
            ['name' => 'Автомобили', 'parent_id' => null],
            ['name' => 'IT услуги', 'parent_id' => null],
            ['name' => 'Образование', 'parent_id' => null],
        ];

        $subActivities = [
            ['name' => 'Мясная продукция', 'parent_name' => 'Еда'],
            ['name' => 'Молочная продукция', 'parent_name' => 'Еда'],
            ['name' => 'Грузовые', 'parent_name' => 'Автомобили'],
            ['name' => 'Легковые', 'parent_name' => 'Автомобили'],
            ['name' => 'Запчасти', 'parent_name' => 'Легковые'],
            ['name' => 'Аксессуары', 'parent_name' => 'Легковые'],
        ];

        // Вставка корневых активностей
        foreach ($rootActivities as $activity) {
            Activity::create(['name' => $activity['name'], 'parent_id' => $activity['parent_id']]);
        }

        // Вставка вложенных активностей
        foreach ($subActivities as $activity) {
            $parent = Activity::where('name', $activity['parent_name'])->first();
            Activity::create(['name' => $activity['name'], 'parent_id' => $parent->id]);
        }
    }
}
