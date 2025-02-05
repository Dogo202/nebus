<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;


class OrganizationActivitySeeder extends Seeder
{
    public function run()
    {
        DB::table('organization_activity')->truncate();


        $activities = [
            'Еда' => [1, 2],
            'Мясная продукция' => [1],
            'Молочная продукция' => [2],
            'Запчасти' => [3],
            'Аксессуары' => [5],
        ];

        foreach ($activities as $activityName => $organizationIds) {
            $activity = Activity::where('name', $activityName)->first();
            foreach ($organizationIds as $organizationId) {
                Organization::find($organizationId)->activities()->attach($activity->id);
            }
        }
    }
}
