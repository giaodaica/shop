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
        'sale_price'];
}
