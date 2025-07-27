<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
            'title' => 'Quản trị viên',
        ]);
        $staffRole = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web',
            'title' => 'Nhân viên',
        ]);

        // Gán toàn bộ quyền cho admin
        $adminRole->givePermissionTo(Permission::all());

        // Gán quyền cho staff
        $modules = ['menu', 'khuyenmai', 'product', 'taikhoan', 'phanquyen'];
        $actions = ['view', 'edit'];
        $staffPermissions = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $staffPermissions[] = "$action $module";
            }
        }
        $staffRole->syncPermissions($staffPermissions);

        // Gán role cho user (ví dụ user id = 1 là admin, id = 2 là staff)
        $adminUser = \App\Models\User::find(1);
        if ($adminUser) $adminUser->assignRole('admin');

        $staffUser = \App\Models\User::find(2);
        if ($staffUser) $staffUser->assignRole('staff');
    }
}
