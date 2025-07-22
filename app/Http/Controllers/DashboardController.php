<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\Products;

class DashboardController extends Controller
{

    public function index()
    {
        // Thống kê cơ bản
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalProducts = Products::count();
        $recentOrders = Order::with(['user', 'addressBook'])->latest()->take(5)->get();

        return view('dashboard.index', compact('totalUsers', 'totalOrders', 'totalProducts', 'recentOrders'));
    }   
} 