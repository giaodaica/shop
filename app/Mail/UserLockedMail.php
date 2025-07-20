<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserLockedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;
    public $note;

    public function __construct(User $user, $reason, $note)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->note = $note;
    }

    public function build()
    {
        return $this->subject('Tài khoản của bạn đã bị khóa')
            ->view('dashboard.pages.users.user_locked');
    }
}
