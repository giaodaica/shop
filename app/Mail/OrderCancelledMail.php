<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $present;
    public $voucher;
    public $type;
    public $final_amount;
    public $data_product;

    /**
     * Create a new message instance.
     */
    public function __construct($present ,$voucher,$type,$final_amount, $data_product)
    {
        $this->present = $present;
        $this->voucher = $voucher;
        $this->type = $type;
        $this->final_amount= $final_amount;
        $this->data_product = $data_product;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo hủy đơn hàng #' . $this->present->code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_cancelled',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
