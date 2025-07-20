<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-vouchers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $now = Carbon::now();

    $affected = DB::table('vouchers')
        ->where('end_date', '<', $now)
        ->where('status', 'active') // chỉ xử lý voucher đang hoạt động
        ->update(['status' => 'expired']);

    Log::info("Đã cập nhật $affected voucher hết hạn lúc $now");
    $this->info("Đã cập nhật $affected voucher hết hạn.");
    }
}
