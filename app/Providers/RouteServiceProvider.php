<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // API-маршруты с префиксом /api
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));

        // Веб-маршруты
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
