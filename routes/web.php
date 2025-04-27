<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware(['cache'])->group(function () {
    Auth::routes();
});
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('google',[HomeController::class,'google'])->name('auth.google');
Route::get('info',[HomeController::class,'info_customer'])->name('info')->middleware('auth','cache');

