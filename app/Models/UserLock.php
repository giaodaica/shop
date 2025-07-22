<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLock extends Model
{
    protected $fillable = ['user_id', 'lock_reason_id', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lockedByUser()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }


    public function reason()
    {
        return $this->belongsTo(LockReason::class, 'lock_reason_id');
    }
}
