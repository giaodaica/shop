<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Minh Dang',
            'email' => 'minhdang15092002@gmail.com',
            'password' => Hash::make('12345678'), 
            'default_address' => 'Hà Nội',
            'default_phone' => '0912345678',
            'role' => 'guest',
            'status' => 'active',
        ]);
    }
}
