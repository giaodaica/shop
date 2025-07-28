<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FlashSale;

class UpdateFlashSaleStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashsale:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái flash sale dựa trên thời gian';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Cập nhật flash sale từ upcoming sang active
        $upcomingToActive = DB::table('flash_sales')
            ->where('status', 'upcoming')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>', $now)
            ->update(['status' => 'active']);

        // Cập nhật flash sale từ active sang ended
        $activeToEnded = DB::table('flash_sales')
            ->where('status', 'active')
            ->where('end_date', '<', $now)
            ->update(['status' => 'ended']);

        Log::info("Flash Sale Status Update - Upcoming to Active: $upcomingToActive, Active to Ended: $activeToEnded at $now");
        $this->info("Đã cập nhật $upcomingToActive flash sale từ upcoming sang active");
        $this->info("Đã cập nhật $activeToEnded flash sale từ active sang ended");
    }
} 