<?php

namespace App\Mail;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param UserInvitation $invitation  The DB record
     * @param string         $registerUrl Already-built URL passed in from the Job's handle()
     *
     * WHY: Building URLs in a Mailable constructor is unsafe when the Mailable
     * is constructed inside a queued Job — the constructor may not have the full
     * app context available during unserialisation. We pass the URL in from
     * handle() where the app is fully booted.
     */
    public function __construct(
        public readonly UserInvitation $invitation,
        public readonly string $registerUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You Are Invited To Join KawachTech',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'roles.mail.invitation',
            with: [
                'invitation'  => $this->invitation,
                'registerUrl' => $this->registerUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}