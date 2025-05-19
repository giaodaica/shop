<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index(){
        return view('pages.shop.checkout');
    }
    public function done(){
        return view('pages.shop.success_checkout');
    }
}
