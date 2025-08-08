<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VouchersHistory extends Model
{
    protected $table = 'vouchers_histories';
    protected $fillable = ['voucher_id',    'user_id',    'user_name',    'from_status',    'to_status',    'note',    'time_action'];
}
