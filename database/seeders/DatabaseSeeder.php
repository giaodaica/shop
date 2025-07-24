<?php

namespace Database\Seeders;

use App\Models\BotQA;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        //Chu thich abc
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call([
        //     UserSeeder::class,
        // ]);
        // BotQA::create([
        //     'question' => 'Giờ làm việc là khi nào?',
        //     'keywords' => 'giờ,làm việc,thời gian',
        //     'answer' => 'Shop hoạt động từ 8h đến 17h, từ thứ 2 đến thứ 7.'
        // ]);

        // $role =  Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        // $user = User::create([
        //     'name' => 'Minh Dang',
        //     'email' => 'minhdang15092002@gmail.com',
        //     'password' => Hash::make('12345678'),
        //     'default_address' => 'Hà Nội',
        //     'default_phone' => '0912345678',
        //     'role' => 'guest',
        //     'status' => 'active',
        // ]);

        // // Gán vai trò cho user
        // if (!$user->hasRole('admin')) {
        //     $user->assignRole($role);
        // }

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            // ColorsSeeder::class,
            // SizesSeeder::class,
            // CategoriesSeeder::class,
            // ProductsSeeder::class,
            // ProductVariantsSeeder::class,
            // LockReasonsSeeder::class
        ]);
    }
}
