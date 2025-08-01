<?php

namespace App\Providers;

use App\Helpers\ViewHelper;
use App\Models\Cart;
use App\Models\CategoriesVouchers;
use App\Models\Vouchers;
use Auth;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if ($this->app->runningInConsole()) {
            $this->app->booted(function () {
                $schedule = app(Schedule::class);

                // chạy command mỗi ngày lúc 00:00
                $schedule->command('app:expire-vouchers')->everyMinute();
                $schedule->command('app:manage-flashsales'::class)->everyMinute();
            });
        }

        // Share ViewHelper với tất cả views
        View::share('ViewHelper', ViewHelper::class);

        View::composer('dashboard.card.menu', function ($view) {
            $menu_voucher = CategoriesVouchers::all();
            $view->with('menu', $menu_voucher);
        });
        View::composer('card.nav', function ($view) {
            $block = [1, 2];
            $vouchers = [];
            foreach ($block as $b) {
                $voucher = Vouchers::where('block', $b)->where('status', 'active')->where('max_used', '>=', '1')->first();
                $vouchers[$b] = $voucher;
            }
            $view->with('vouchers', $vouchers);
        });
        View::composer('card.nav', function ($view) {
            if (Auth::check()) {
                $cartItems = Cart::with('product', 'productVariant')->where('user_id', Auth::id())->get();
                $cartCount = $cartItems->count();
                $cartSubtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price_at_time);
            } else {
                $cartItems = collect();
                $cartCount = 0;
                $cartSubtotal = 0;
            }
            // dd($cartItems);
            $view->with([
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
                'cartSubtotal' => $cartSubtotal,
            ]);
        });
        Paginator::useBootstrapFive();
    }
}
