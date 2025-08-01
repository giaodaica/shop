<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DashboardAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            return abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $user = Auth::user();

        // Kiểm tra user có permission truy cập dashboard không
        if (!$user->hasPermissionTo('Xem trang dashboard')) {
            return abort(403, 'Bạn không có quyền truy cập dashboard.');
        }

        return $next($request);
    }
} 