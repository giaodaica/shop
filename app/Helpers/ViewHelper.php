<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class ViewHelper
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
     * Kiểm tra user có thể truy cập dashboard không
     */
    public static function canAccessDashboard()
    {
        return self::hasPermission('Xem trang dashboard');
    }

    /**
     * Kiểm tra user có thể quản lý users không
     */
    public static function canManageUsers()
    {
        return self::hasPermission('Quản lý Tài khoản');
    }

    /**
     * Kiểm tra user có thể quản lý products không
     */
    public static function canManageProducts()
    {
        return self::hasPermission('Quản lý Sản phẩm');
    }

    /**
     * Kiểm tra user có thể quản lý orders không
     */
    public static function canManageOrders()
    {
        return self::hasPermission('Quản lý Đơn hàng');
    }

    /**
     * Kiểm tra user có thể quản lý vouchers không
     */
    public static function canManageVouchers()
    {
        return self::hasPermission('Quản lý Voucher');
    }

    /**
     * Lấy tên hiển thị của user
     */
    public static function getUserDisplayName()
    {
        if (!Auth::check()) {
            return 'Khách';
        }
        
        $user = Auth::user();
        return $user->name ?: $user->email;
    }

    /**
     * Kiểm tra user có phải là admin không
     */
    public static function isAdmin()
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->role === 'admin' || $user->hasRole('Quản trị viên');
    }

    /**
     * Lấy roles của user dưới dạng string
     */
    public static function getUserRolesString()
    {
        if (!Auth::check()) {
            return '';
        }
        
        $user = Auth::user();
        $roles = $user->getRoleNames();
        
        return $roles->implode(', ');
    }

    /**
     * Lấy permissions của user dưới dạng string
     */
    public static function getUserPermissionsString()
    {
        if (!Auth::check()) {
            return '';
        }
        
        $user = Auth::user();
        $permissions = $user->getAllPermissions();
        
        return $permissions->pluck('name')->implode(', ');
    }
} 