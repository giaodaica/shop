<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_books_id',
        'voucher_id',
        'name',
        'phone',
        'address',
        'total_amount',
        'final_amount',
        'discount_amount',
        'status',
        'code_order',
        'pay_method',
        'status_pay',
        'notes',
        'shipping_fee',
        'shipping_method',
        'image_ship',
        'image_user',
        'user_comment',
        'user_confirm',
        'province_code',
        'wards_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addressBook()
    {
        return $this->belongsTo(AddressBook::class, 'address_books_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Vouchers::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderHistories()
    {
        return $this->hasMany(OrderHistories::class);
    }
}
