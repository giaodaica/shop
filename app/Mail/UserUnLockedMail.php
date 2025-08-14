<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserUnLockedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;
    public $note;

    public function __construct(User $user)
    {
        $this->user = $user;
      
    }

    public function build()
    {
        return $this->subject('Tài khoản của bạn đã được mở khóa')
            ->view('dashboard.pages.users.user_unlocked');
    }
}
