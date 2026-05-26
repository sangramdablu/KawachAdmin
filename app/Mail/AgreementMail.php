<?php

namespace App\Mail;

use App\Models\BillingAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BillingAgreement $agreement,
        public string           $pdfPath,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Software Development Agreement — Kawach Technology (Invoice #' . $this->agreement->invoice_no . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'billing.mail.agreement_email',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                      ->as('Agreement_' . $this->agreement->invoice_no . '.pdf')
                      ->withMime('application/pdf'),
        ];
    }
}