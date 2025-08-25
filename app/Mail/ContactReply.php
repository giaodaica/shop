<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $reply_title;
    public $admin_reply;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $reply_title, $admin_reply, $contact)
    {
        $this->name = $name;
        $this->reply_title = $reply_title;
        $this->admin_reply = $admin_reply;
        $this->contact = $contact;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->reply_title)
            ->view('emails.contact_reply')
            ->with([
                'name' => $this->name,
                'contact' => $this->contact,
                'admin_reply' => $this->admin_reply,
            ]);
    }
}
