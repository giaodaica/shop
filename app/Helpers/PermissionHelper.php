<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Kiểm tra user có permission không
     */
    public static function hasPermission($permission)
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasPermissionTo($permission);
    }

    /**
     * Kiểm tra user có role không
     */
    public static function hasRole($role)
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasRole($role);
    }

    /**
     * Kiểm tra user có bất kỳ permission nào trong danh sách không
     */
    public static function hasAnyPermission($permissions)
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasAnyPermission($permissions);
    }

    /**
     * Kiểm tra user có tất cả permissions trong danh sách không
     */
    public static function hasAllPermissions($permissions)
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasAllPermissions($permissions);
    }

    /**
     * Lấy tất cả permissions của user
     */
    public static function getUserPermissions()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return Auth::user()->getAllPermissions();
    }

    /**
     * Lấy tất cả roles của user
     */
    public static function getUserRoles()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return Auth::user()->getRoleNames();
    }

    /**
     * Lấy permissions theo module
     */
    public static function getPermissionsByModule($moduleName)
    {
        if (!Auth::check()) {
            return collect();
        }
        
        $user = Auth::user();
        $allPermissions = $user->getAllPermissions();
        
        return $allPermissions->filter(function ($permission) use ($moduleName) {
            return $permission->name === $moduleName || 
                   (strpos($permission->name, $moduleName) !== false && $permission->parent_id !== null);
        });
    }

    /**
     * Kiểm tra user có thể thực hiện hành động không
     */
    public static function can($action, $module = null)
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        if ($module) {
            return $user->hasPermissionTo("$action $module");
        }
        
        return $user->hasPermissionTo($action);
    }

    /**
     * Lấy danh sách permissions theo nhóm
     */
    public static function getGroupedPermissions()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        $user = Auth::user();
        $permissions = $user->getAllPermissions();
        
        return $permissions->groupBy('parent_id');
    }

    /**
     * Kiểm tra user có quyền truy cập dashboard không
     */
    public static function canAccessDashboard()
    {
        return self::hasPermission('Xem trang dashboard');
    }

    /**
     * Kiểm tra user có quyền quản lý users không
     */
    public static function canManageUsers()
    {
        return self::hasPermission('Quản lý Tài khoản');
    }

    /**
     * Kiểm tra user có quyền quản lý roles không
     */
    public static function canManageRoles()
    {
        return self::hasPermission('Quản lý Vai trò');
    }

    /**
     * Kiểm tra user có quyền quản lý permissions không
     */
    public static function canManagePermissions()
    {
        return self::hasPermission('Quản lý Quyền hạn');
    }
} 