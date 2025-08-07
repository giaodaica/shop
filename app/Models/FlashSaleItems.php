<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleItems extends Model
{
    protected $table = 'flash_sale_items';
    protected $fillable = [
        'product_variant_id',
        'flash_sale_id',
        'name',
        'variant_image_url',
        'max_quantity',
        'sold_quantity',
        'price_at_flash_sale',
        'product_id',
        'color_id',
        'size_id',
        'import_price',
        'listed_price',
        'sale_price',
        'stock_quantity'
    ];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(Product_variants::class, 'product_variant_id');
    }



    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

}
