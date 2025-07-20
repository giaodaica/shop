<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundMoney extends Model
{

    protected $table = 'refund_money';

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'status',
        'bank',
        'bank_account_name',
        'reason',
        'images',
        'QR_images',
        'stk',
        'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
