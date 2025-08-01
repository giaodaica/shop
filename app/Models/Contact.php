<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $fillable = [
        'user_id',	'name',	'phone',	'email',	'title',	'content',	'admin_reply',	'time_reply',	'is_replied'
    ];
}
