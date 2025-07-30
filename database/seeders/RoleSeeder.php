<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [

            'admin' => [
                'title' => 'Admin',
                'name' => 'Quản trị viên',
            ],
            'staff' => [
                'title' => 'Staff',
                'name' => 'Nhân viên',

            ],
        ];

        foreach ($roles as $roleKey => $roleData) {
            $role = Role::firstOrCreate([
                'title' => $roleData['title'],
                'name' => $roleData['name'],
            ]);

            // Gán permissions theo role
            $this->assignPermissionsToRole($role, $roleKey);
        }
    }

    private function assignPermissionsToRole(Role $role, string $roleKey)
    {
        switch ($roleKey) {
            case 'admin':
                // Super Admin có tất cả permissions
                $permissions = Permission::all();
                $role->syncPermissions($permissions);
                break;
            case 'staff':
                $permissions = Permission::whereIn('name', [
                    'Quản lý Dashboard',
                    'Xem trang dashboard',
                    'Quản lý Đơn hàng',
                    'Xem trang đơn hàng',
                    'Sửa đơn hàng',
                    'Thay đổi trạng thái đơn hàng',
                    'Quản lý Hoàn tiền',
                    'Xem trang hoàn tiền',
                    'Phê duyệt hoàn tiền',
                    'Từ chối hoàn tiền',
                    'Quản lý Doanh thu',
                    'Xem trang doanh thu',
                    'Quản lý Bình luận',
                    'Xem trang bình luận',
                    'Sửa bình luận',
                    'Trả lời bình luận'
                ])->get();
                $role->syncPermissions($permissions);
                break;
        }
    }
}
