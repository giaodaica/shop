<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'discount',
        'start_date',
        'end_date',
        'status',
        'slot_time',
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];



    public function items()
    {
        return $this->hasMany(FlashSaleItems::class, 'flash_sale_id');
    }


}
