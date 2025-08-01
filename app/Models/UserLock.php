<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLock extends Model
{
  
    protected $fillable = ['user_id', 'lock_reason_id', 'note', 'locked_by'];

    // Người bị khóa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Người thực hiện hành động khóa
    public function lockedByUser()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    // Lý do khóa
    public function reason()
    {
        return $this->belongsTo(LockReason::class, 'lock_reason_id');
    }
}
