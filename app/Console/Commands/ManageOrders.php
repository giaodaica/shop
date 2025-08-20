<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class ManageOrders extends Command
{
    protected $signature = 'orders:auto-delivered';
    protected $description = 'Tự động chuyển trạng thái đơn hàng từ success sang delivered sau 1 ngày';

    public function handle()
    {
        $count = Order::where('status', 'success')
            ->where('updated_at', '<=', Carbon::now()->subDay())
            ->update(['status' => 'delivered']);

        $this->info("Đã chuyển $count đơn hàng sang trạng thái delivered.");
    }
}
