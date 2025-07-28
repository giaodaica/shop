<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\FlashSaleItems;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index(){
        $flashSales  = FlashSale::all();
        return view('dashboard.pages.flashsale.index',compact('flashSales'));
    }
    public function show($id){
        $variants = FlashSaleItems::where('flash_sale_id',$id)
        ->join('colors','flash_sale_items.color_id','colors.id')
        ->join('sizes','flash_sale_items.size_id','sizes.id')
        ->get();
        // dd($variants);
        $flash_sale_id = $id;
        return view('dashboard.pages.flashsale.show',compact('variants','flash_sale_id'));
    }
}
