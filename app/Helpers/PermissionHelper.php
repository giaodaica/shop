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

    /**
     * Kiểm tra permission theo hierarchy (cha-con)
     * Nếu user có permission cha, tự động có quyền truy cập permission con
     */
    public static function hasPermissionHierarchy($permissionName)
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Kiểm tra permission trực tiếp
        if ($user->hasPermissionTo($permissionName)) {
            return true;
        }
        
        // Kiểm tra permission cha
        $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
        if (!$permission) {
            return false;
        }
        
        // Tìm tất cả permission cha
        $parentPermissions = self::getParentPermissions($permission);
        
        foreach ($parentPermissions as $parentPermission) {
            if ($user->hasPermissionTo($parentPermission->name)) {
                return true;
            }
        }
        
        // Kiểm tra xem permission hiện tại có phải là permission cha không
        // Nếu có permission cha, thì cũng có quyền truy cập tất cả permission con
        $childPermissions = self::getAllChildPermissions($permission->id);
        if ($childPermissions->count() > 0) {
            // Đây là permission cha, kiểm tra xem user có permission cha này không
            if ($user->hasPermissionTo($permissionName)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Lấy tất cả permission cha của một permission
     */
    public static function getParentPermissions($permission)
    {
        $parents = collect();
        $currentPermission = $permission;
        
        while ($currentPermission && $currentPermission->parent_id) {
            $parent = \Spatie\Permission\Models\Permission::find($currentPermission->parent_id);
            if ($parent) {
                $parents->push($parent);
                $currentPermission = $parent;
            } else {
                break;
            }
        }
        
        return $parents;
    }

    /**
     * Lấy tất cả permission con của một permission
     */
    public static function getChildPermissions($permissionId)
    {
        return \Spatie\Permission\Models\Permission::where('parent_id', $permissionId)->get();
    }

    /**
     * Lấy tất cả permission con (recursive) của một permission
     */
    public static function getAllChildPermissions($permissionId)
    {
        $children = collect();
        $directChildren = \Spatie\Permission\Models\Permission::where('parent_id', $permissionId)->get();
        
        foreach ($directChildren as $child) {
            $children->push($child);
            $children = $children->merge(self::getAllChildPermissions($child->id));
        }
        
        return $children;
    }

    /**
     * Kiểm tra user có quyền truy cập module theo hierarchy
     */
    public static function canAccessModule($moduleName)
    {
        return self::hasPermissionHierarchy("Xem $moduleName") || 
               self::hasPermissionHierarchy("Quản lý $moduleName");
    }

    /**
     * Kiểm tra permission linh hoạt - có thể kiểm tra cả permission cha và con
     */
    public static function hasFlexiblePermission($permissionName)
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Kiểm tra permission trực tiếp
        if ($user->hasPermissionTo($permissionName)) {
            return true;
        }
        
        // Tìm permission trong database
        $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
        if (!$permission) {
            return false;
        }
        
        // Nếu đây là permission cha, kiểm tra xem có permission con nào được cấp không
        $childPermissions = self::getAllChildPermissions($permission->id);
        if ($childPermissions->count() > 0) {
            foreach ($childPermissions as $childPermission) {
                if ($user->hasPermissionTo($childPermission->name)) {
                    return true;
                }
            }
        }
        
        // Nếu đây là permission con, kiểm tra permission cha
        if ($permission->parent_id) {
            $parentPermission = \Spatie\Permission\Models\Permission::find($permission->parent_id);
            if ($parentPermission && $user->hasPermissionTo($parentPermission->name)) {
                return true;
            }
        }
        
        return false;
    }
} 