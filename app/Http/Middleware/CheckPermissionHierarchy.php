<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class CheckPermissionHierarchy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!PermissionHelper::hasFlexiblePermission($permission)) {
            return abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
} 