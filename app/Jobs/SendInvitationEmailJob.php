<?php

namespace App\Jobs;

use App\Mail\UserInvitationMail;
use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvitationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60;

    public function backoff(): array
    {
        return [30, 60, 120];
    }

    public function __construct(public UserInvitation $invitation)
    {
        //
        // NOTE: Do NOT build URLs or do any logic here.
        // The constructor runs at dispatch time (serialisation).
        // Properties set here are NOT re-hydrated when the worker
        // picks up the job — only the model ID is stored/restored.
        // All logic belongs in handle().
    }

    public function handle(): void
    {
        // Re-check: don't send if already accepted (safe guard for retries)
        if ($this->invitation->isAccepted()) {
            Log::info('SendInvitationEmailJob: invitation already accepted, skipping.', [
                'invitation_id' => $this->invitation->id,
            ]);
            return;
        }

        // Re-check: don't send if expired
        if ($this->invitation->isExpired()) {
            Log::info('SendInvitationEmailJob: invitation expired, skipping.', [
                'invitation_id' => $this->invitation->id,
            ]);
            return;
        }

        // Build the URL here in handle() — NOT in the constructor or Mailable constructor.
        // The token stored in DB is the raw token (see UserInvitation::generate()).
        $registerUrl = route('invitation.accept', $this->invitation->token);
        // $registerUrl = url('/register?token=' . $this->invitation->token);

        Mail::to($this->invitation->email)
            ->send(new UserInvitationMail($this->invitation, $registerUrl));

        Log::info('Invitation email sent.', [
            'invitation_id' => $this->invitation->id,
            'email'         => $this->invitation->email,
        ]);
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendInvitationEmailJob failed permanently.', [
            'invitation_id' => $this->invitation->id,
            'email'         => $this->invitation->email,
            'error'         => $e->getMessage(),
        ]);
    }
}