<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItems;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    public function index()
    {
        // Lấy flash sale đang active
        $activeFlashSale = FlashSale::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('itemsWithProduct')
            ->first();

        // Lấy các flash sale sắp diễn ra (upcoming)
        $upcomingFlashSales = FlashSale::where('status', 'upcoming')
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(3) // Lấy 3 flash sale sắp diễn ra gần nhất
            ->get();

        // Tính thời gian còn lại cho flash sale active
        $remainingTime = null;
        if ($activeFlashSale) {
            $endTime = Carbon::parse($activeFlashSale->end_date);
            $now = Carbon::now();
            $remainingTime = $endTime->diff($now);
        }

        return view('card.new_product', compact(
            'activeFlashSale',
            'upcomingFlashSales',
            'remainingTime'
        ));
    }
} 