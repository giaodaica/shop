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

    public function login()
    {
        return view('dashboard.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.login');
    }
} 