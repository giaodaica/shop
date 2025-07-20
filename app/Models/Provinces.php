<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $table = 'provinces';

    protected $fillable = [
        'province_code',
        'name',
        'short_name',
        'code',
        'place_type',
        'country'
    ];

    public function wards()
    {
        return $this->hasMany(Wards::class, 'province_code', 'province_code');
    }

    public function addressBooks()
    {
        return $this->hasMany(AddressBook::class, 'province_code', 'province_code');
    }
}
