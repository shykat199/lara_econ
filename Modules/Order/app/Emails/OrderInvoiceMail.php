<?php

namespace Modules\Order\Emails;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your invoice for order {$this->order->order_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'order::emails.invoice',
            with: ['order' => $this->order],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('order::invoice', ['order' => $this->order]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                "invoice-{$this->order->order_number}.pdf",
            )->withMime('application/pdf'),
        ];
    }
}
