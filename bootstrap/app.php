<?php

use App\Http\Middleware\NoCacheMiddleware;
use App\Http\Middleware\DashboardAuth;
use App\Http\Middleware\DashboardGuest;
use App\Http\Middleware\CheckPermissionHierarchy;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'cache' => NoCacheMiddleware::class,
            'dashboard.auth' => DashboardAuth::class,
            'dashboard.guest' => DashboardGuest::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'permission.hierarchy' => CheckPermissionHierarchy::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
