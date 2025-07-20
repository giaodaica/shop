<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockReason extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function userLocks()
    {
        return $this->hasMany(UserLock::class);
    }
}
