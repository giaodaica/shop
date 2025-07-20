<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundLogs extends Model
{
    protected $table = 'refund_logs';
    protected $fillable = ['user_id','money','action','refund_id','notes'];
}
