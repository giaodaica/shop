<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'content',
        'admin_reply',
        'rating',
        'is_show',
        'images', 
        'order_id',
        'product_variant_id',
        'color',
        'size',
    ];

    protected $casts = [
        'images' => 'array', // 👈 Ép kiểu JSON thành mảng
    ];

    
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
    public function productVariant()
    {
        return $this->belongsTo(Product_variants::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function replies()
{
    return $this->hasMany(ReviewReply::class);
}
// App\Models\Review.php
public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}

protected $a = 2;
}
