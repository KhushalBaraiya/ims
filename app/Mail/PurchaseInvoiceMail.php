<?php

namespace App\Mail;

use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Purchase $purchase) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Order Invoice — ' . $this->purchase->purchase_no,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase-invoice',
        );
    }
}
