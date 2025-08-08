<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'flash_sale_items_id',
        'product_id',
        'product_name',
        'product_image_url',
        'import_price',
        'listed_price',
        'sale_price',
        'quantity',
        'promotion_type',
        'color_name',
        'size_name'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(Product_variants::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function flashSaleItem()
    {
        return $this->belongsTo(Flash_sale_items::class, 'flash_sale_items_id');
    }
}
