<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LockReason;

class LockReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $reasons = [
            'Vi phạm chính sách',
            'Spam hệ thống',
            'Thông tin giả mạo',
            'Nghi ngờ gian lận',
            'Yêu cầu từ người dùng',
        ];

        foreach ($reasons as $reason) {
            LockReason::create(['name' => $reason]);
        }
    }
}
