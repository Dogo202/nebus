<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    // Метод для обработки запроса
    public function handle(Request $request, Closure $next)
    {

        // Получаем API ключ из заголовков запроса
        $apiKey = $request->header('X-API-Key');

        // Проверяем, что ключ соответствует статическому значению
        if ($apiKey !== env('API_KEY')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

}
