<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.shop.index');
    }
    public function info_customer(){
        return view('pages.shop.account');
    }
    public function show($id){
        return view('pages.shop.show');
    }
    public function admin(){
        return view('dashboard.index');
    }
}
