<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireVouchers extends Command
{
    protected $signature = 'app:expire-vouchers';
    protected $description = 'Kích hoạt, kết thúc và hết hạn voucher tự động';

    public function handle()
    {
        $now = Carbon::now();
        $activated = 0;
        $finished = 0;
        $expired = 0;

        // 1. Kích hoạt voucher khi đến hạn
        $activated = DB::table('vouchers')
            ->where('start_date', '<=', $now)
            ->where('status', 'draft')
            ->whereNotNull('block')
            ->update(['status' => 'active']);

        // 2. Tự kết thúc nếu số lượng đã hết
        $finished = DB::table('vouchers')
            ->where('status', 'active')
            ->where('max_used', '<=', 0)
            ->update(['status' => 'used_up']);

        // 3. Hết hạn theo thời gian
        $expired = DB::table('vouchers')
            ->where('end_date', '<', $now)
            ->where('status', 'active')
            ->update(['status' => 'expired']);
        //3.1 hết hạn voucher user
        $expired1 = DB::table('vouchers_users')
            ->where('end_date', '<', $now)
            ->where('status', 'active')
            ->update(['status' => 'expired']);
        // 4. Chuyển voucher về trạng thái save nếu là used_up hoặc expired
        $saved = DB::table('vouchers')
            ->whereIn('status', ['used_up', 'expired'])
            ->update(['status' => 'save']);

        Log::info("CRON VOUCHER: Kích hoạt $activated | Kết thúc $finished | Hết hạn $expired lúc $now| Hết hạn user: $expired1|Lưu trữ: $saved");
        $this->info("Đã cập nhật: $activated voucher kích hoạt, $finished kết thúc, $expired hết hạn, $expired1 hết hạn user, $saved lưu trữ");
    }
}
