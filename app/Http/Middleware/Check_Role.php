<?php

namespace App\Http\Middleware; // Пространство имен middleware(промежуточное программное обеспечение)

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Check_Role
{
    public function handle(Request $request, Closure $next, ...$roles) : Response
    {
        if(!$request->user())
        {
            return response()->json(['message' => 'Вы не авторизованы'], 401);
        }

        if(!in_array($request->user()->role, $roles))
        {
            return response()->json(['message' => 'У вас нет доступа'], 403);
        }

        return $next($request);
    }
}