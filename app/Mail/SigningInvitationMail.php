<?php

namespace App\Mail;

use App\Models\BillingAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SigningInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly BillingAgreement $agreement,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: Please Sign Your Agreement — ' . $this->agreement->project_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'billing.mail.signing-invitation',
        );
    }
}