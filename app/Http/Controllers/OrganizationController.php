<?php
// app/Http/Controllers/OrganizationController.php
namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Activity;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    // Список всех организаций в здании
    public function getOrganizationsByBuilding($buildingId)
    {
        $organizations = Organization::where('building_id', $buildingId)
            ->with(['phoneNumbers'])
            ->get()->map(function ($organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'building_id' => $organization->building_id,
                    'created_at' => $organization->created_at,
                    'updated_at' => $organization->updated_at,
                    'phone_numbers' => $organization->phoneNumbers->pluck('phone_number')
                ];
            });

        if ($organizations->isEmpty()) {
            return response()->json(['error' => 'Organization not found'], 404);
        }
        return response()->json($organizations);
    }

    // Список организаций по виду деятельности
    public function getOrganizationsByActivity($activityId)
    {
        // Получаем все виды деятельности, начиная с указанной активности, и их дочерние виды деятельности
        $activityIds = $this->getActivityIdsIncludingChildren($activityId);

        // Находим организации, которые связаны с любым из этих видов деятельности
        $organizations = Organization::whereHas('activities', function ($query) use ($activityIds) {
            // Ищем по всем найденным ID видов деятельности
            $query->whereIn('activity_id', $activityIds);
        })->with(['phoneNumbers', 'activities'])  // Подключаем связи для вывода телефонов и активностей
        ->get()
        ->map(function ($organization) {
            return [
                'id' => $organization->id,
                'name' => $organization->name,
                'building_id' => $organization->building_id,
                'created_at' => $organization->created_at,
                'updated_at' => $organization->updated_at,
                'phone_numbers' => $organization->phoneNumbers->pluck('phone_number'),
                'activities' => $organization->activities->pluck('name')
            ];
        });

        if ($organizations->isEmpty()) {
            return response()->json(['error' => 'No organizations found for this activity'], 404);
        }

        return response()->json($organizations);
    }

    private function getActivityIdsIncludingChildren($activityId)
    {
        // Ищем все дочерние виды деятельности
        $activity = Activity::with('childActivities')->find($activityId);
        if (!$activity) {
            return [];  // Или можно выбросить исключение
        }

        // Собираем ID всех дочерних видов деятельности, включая сам вид
        $activityIds = [$activity->id];
        // Проверяем, есть ли дочерние виды деятельности, и если есть, рекурсивно добавляем их ID
        if ($activity->childActivities->isNotEmpty()) {
            $this->getChildActivityIds($activity, $activityIds);
        }

        return $activityIds;
    }

    private function getChildActivityIds($activity, &$activityIds)
    {
        if ($activity->childActivities) {
            foreach ($activity->childActivities as $child) {
                $activityIds[] = $child->id;  // Добавляем дочернюю активность в массив
                $this->getChildActivityIds($child, $activityIds);  // Рекурсивно ищем дочерние активности для текущей
            }
        }
    }

//    public function getOrganizationsByActivityv2($activityId)
//    {
//        $organizations = Organization::whereHas('activities', function ($query) use ($activityId) {
//            $query->where('activities.id', $activityId);
//        })->get()->map(function ($organization) {
//            // Собираем активности и их дочерние активности (если есть)
//            $activities = $organization->activities->map(function ($activity) {
//                // Получаем все вложенные (дочерние) активности для текущей активности
//                $children = $activity->childActivities()->pluck('name'); // Получаем имена дочерних активностей
//
//                // Возвращаем текущую активность вместе с её дочерними
//                return [
//                    'name' => $activity->name,
//                    'children' => $children
//                ];
//            });
//
//            return [
//                'id' => $organization->id,
//                'name' => $organization->name,
//                'building_id' => $organization->building_id,
//                'created_at' => $organization->created_at,
//                'updated_at' => $organization->updated_at,
//                'phone_numbers' => $organization->phoneNumbers->pluck('phone_number'),
//                'activities' => $activities
//            ];
//        });
//
//        if ($organizations->isEmpty()) {
//            return response()->json(['error' => 'Organization not found'], 404);
//        }
//
//        return response()->json($organizations);
//    }


    // Список организаций в заданном радиусе
    public function getOrganizationsByLocation(Request $request)
    {
        $latitudeMin = $request->get('latitude_min');
        $latitudeMax = $request->get('latitude_max');
        $longitudeMin = $request->get('longitude_min');
        $longitudeMax = $request->get('longitude_max');


        // Получаем здания в пределах прямоугольной области
        $buildings = Building::whereBetween('latitude', [$latitudeMin, $latitudeMax])
            ->whereBetween('longitude', [$longitudeMin, $longitudeMax])
            ->with('organizations')
            ->get();

        $organizations = $buildings->flatMap(function ($building) {
            return $building->organizations; // Извлекаем все организации из зданий
        });

        if ($organizations->isEmpty()) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        return response()->json($organizations);
    }

    // Информация о конкретной организации
    public function getOrganizationById($id)
    {
        $organization = Organization::with(['phoneNumbers', 'activities'])->find($id);

        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        $response = [
            'id' => $organization->id,
            'name' => $organization->name,
            'building_id' => $organization->building_id,
            'created_at' => $organization->created_at,
            'updated_at' => $organization->updated_at,
            'phone_numbers' => $organization->phoneNumbers->pluck('phone_number'), // Извлекаем только номера телефонов
            'activities' => $organization->activities->pluck('name') // Извлекаем только имена видов деятельности
        ];


        return response()->json($response);
    }

    public function getOrganizationByIdv2($id)
    {
        $organization = Organization::with(['phoneNumbers', 'activities'])->find($id);

        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        $response = [
            'id' => $organization->id,
            'name' => $organization->name,
            'building_id' => $organization->building_id,
            'created_at' => $organization->created_at,
            'updated_at' => $organization->updated_at,
            'phone_numbers' => $organization->phoneNumbers->pluck('phone_number'),
            'activities' => $organization->activities->map(function ($activity) {
                // Для каждой активности добавляем дочерние виды деятельности, если они есть
                $children = $activity->childActivities->pluck('name'); // Получаем только имена дочерних видов деятельности

                // Если есть дочерние виды деятельности, добавляем их в ответ
                return [
                    'name' => $activity->name,
                    'children' => $children
                ];
            })
        ];

        return response()->json($response);
    }

    // Поиск по виду деятельности (с учётом подкатегорий)
    public function searchOrganizationsByActivity($activityName)
    {
        $organizations = Organization::whereHas('activities', function ($query) use ($activityName) {
            $query->where('name', 'like', "%$activityName%");
        })
            ->with(['phoneNumbers'])
            ->get()->map(function ($organization) {
                // Фильтруем виды деятельности, чтобы исключить вложенные
                $activities = $organization->activities->filter(function ($activity) {
                    // Проверяем, если у активности есть вложенные виды (с parent_activity_id != null)
                    return !$activity->parent_activity_id;
                })->pluck('name');  // Получаем только название активности

                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'building_id' => $organization->building_id,
                    'created_at' => $organization->created_at,
                    'updated_at' => $organization->updated_at,
                    'phone_numbers' => $organization->phoneNumbers->pluck('phone_number'),
                ];
            });

        if ($organizations->isEmpty()) {
            return response()->json(['error' => 'Organization not found'], 404);
        }


        return response()->json($organizations);
    }

    // Поиск организации по названию
    public function searchOrganizationsByName($name)
    {
        $organization = Organization::where('name', 'like', "%$name%")
            ->with([ 'phoneNumbers', 'activities'])
            ->first();

        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        $response = [
            'id' => $organization->id,
            'name' => $organization->name,
            'building_id' => $organization->building_id,
            'created_at' => $organization->created_at,
            'updated_at' => $organization->updated_at,
            'phone_numbers' => $organization->phoneNumbers->pluck('phone_number'), // Извлекаем только номера телефонов
            'activities' => $organization->activities->pluck('name') // Извлекаем только имена видов деятельности
        ];


        return response()->json($response);
    }
}
