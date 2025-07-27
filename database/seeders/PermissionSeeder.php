<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'menu',
            'khuyenmai', // khuyến mãi
            'product',
            'taikhoan', // tài khoản
            'phanquyen', // phân quyền
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$action $module"]);
            }
        }
    }
} 