<?php

namespace App\Models;

use Database\Factories\ProductVariantsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_variants extends Model
{
    use SoftDeletes; use HasFactory;
    protected $table ='product_variants';
    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'name',
        'variant_image_url',
        'import_price',
        'listed_price',
        'sale_price',
        'stock',
        'initial_stock',
        'is_show'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

}

