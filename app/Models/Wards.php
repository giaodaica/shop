<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wards extends Model
{
    protected $table = 'wards';

    protected $fillable = [
        'ward_code',
        'name',
        'province_code'
    ];

    public function province()
    {
        return $this->belongsTo(Provinces::class, 'province_code', 'province_code');
    }

    public function addressBooks()
    {
        return $this->hasMany(AddressBook::class, 'ward_code', 'ward_code');
    }
}
