<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'voucher_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function voucher()
    {
        return $this->belongsTo(Vouchers::class, 'voucher_id');
    }

    public function items()
    {
        return $this->hasMany(FlashSaleItems::class, 'flash_sale_id');
    }

    public function itemsWithProduct()
    {
        return $this->hasMany(FlashSaleItems::class, 'flash_sale_id')
            ->with(['productVariant.product', 'productVariant.color', 'productVariant.size']);
    }

    // Scope để lấy flash sale active
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope để lấy flash sale upcoming
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    // Lấy tất cả flash sale active với items
    public static function getActiveFlashSales()
    {
        return self::active()
            ->with('itemsWithProduct')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();
    }

    // Lấy tất cả flash sale upcoming với items
    public static function getUpcomingFlashSales()
    {
        return self::upcoming()
            ->with('itemsWithProduct')
            ->where('start_date', '>', now())
            ->get();
    }
}
