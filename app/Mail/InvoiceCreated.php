<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;

class InvoiceCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public ?string $pdfData;

    public function __construct(Invoice $invoice, ?string $pdfData = null)
    {
        $this->invoice = $invoice;
        $this->pdfData = $pdfData;
    }

    public function build()
    {
        $mail = $this->subject('Factura ' . $this->invoice->invoice_number)
                     ->view('emails.invoice_created')
                     ->with(['invoice' => $this->invoice]);

        if ($this->pdfData) {
            $mail->attachData($this->pdfData, 'factura_' . $this->invoice->invoice_number . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
