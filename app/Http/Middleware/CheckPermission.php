<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $routeName = $request->route()->getName(); // chính là slug cần kiểm tra

        if (!$user) {
            return response()->view('errors.forbidden', [], 403);
        }

        // Nếu là admin tổng (vai_tro_id = 1) thì bypass
        if ($user->vai_tro_id == 1) {
            return $next($request);
        }

        // Lấy danh sách slug mà user có quyền (qua vai trò)
        $slugs = $user->phanQuyens->pluck('slug')->toArray();

        if (!in_array($routeName, $slugs)) {
            return response()->view('errors.forbidden', [], 403);
        }

        return $next($request);
    }
}
