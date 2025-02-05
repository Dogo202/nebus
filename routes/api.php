<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Middleware\CheckApiKey;


//Route::get('organizations/building/{buildingId}', [OrganizationController::class, 'getOrganizationsByBuilding']);

Route::middleware([CheckApiKey::class])->group(function () {
    Route::get('organizations/building/{buildingId}', [OrganizationController::class, 'getOrganizationsByBuilding']);
    Route::get('organizations/activity/{activityId}', [OrganizationController::class, 'getOrganizationsByActivity']);
    Route::get('organizations/location', [OrganizationController::class, 'getOrganizationsByLocation']);
    Route::get('organization/{id}', [OrganizationController::class, 'getOrganizationById']);
    Route::get('organizationv2/{id}', [OrganizationController::class, 'getOrganizationByIdv2']);
    Route::get('organizations/search/activity/{activityName}', [OrganizationController::class, 'searchOrganizationsByActivity']);
    Route::get('organizations/search/name/{name}', [OrganizationController::class, 'searchOrganizationsByName']);
});

