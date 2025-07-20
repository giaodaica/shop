<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'user_id',
        'province_code',
        'ward_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'address_books_id');
    }

    public function province()
    {
        return $this->belongsTo(Provinces::class, 'province_code', 'province_code');
    }

    public function ward()
    {
        return $this->belongsTo(Wards::class, 'ward_code', 'ward_code');
    }
}
