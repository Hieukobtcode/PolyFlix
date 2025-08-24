<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminChiNhanh
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Kiểm tra đăng nhập
        if (!$user) {
            return redirect()->route('login');
        }

        // Kiểm tra vai trò admin chi nhánh
        if ($user->vai_tro_id != 2) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        // Kiểm tra admin có được gán chi nhánh không
        if (!$user->chiNhanhDangQuanLy) {
            abort(403, 'Bạn chưa được gán quản lý chi nhánh nào.');
        }

        return $next($request);
    }
}
