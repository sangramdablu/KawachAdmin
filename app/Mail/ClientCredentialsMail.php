<?php

namespace App\Mail;

use App\Models\BillingAgreement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientCredentialsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User             $clientUser,
        public readonly BillingAgreement $agreement,
        public readonly string           $plainPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your KawachTech Client Portal Access — ' . $this->agreement->project_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-credentials',
        );
    }
}