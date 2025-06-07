<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoleIds = [1, 2, 3]; // Chỉ cho các vai trò có ID 1, 2, 3

        if (!auth()->check() || !in_array(auth()->user()->vai_tro_id, $allowedRoleIds)) {
            return response()->view('errors.forbidden', [], 403);
        }

        return $next($request);
    }
}
