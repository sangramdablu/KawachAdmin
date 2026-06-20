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

class WelcomeAndCredentialsMail extends Mailable implements ShouldQueue
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
            subject: 'Welcome to KawachTech — Your Portal Access is Ready 🚀',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'billing.mail.welcome-credentials',
        );
    }
}