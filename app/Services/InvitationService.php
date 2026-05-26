<?php

namespace App\Services;

use App\Jobs\SendInvitationEmailJob;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Validation\ValidationException;

class InvitationService
{
    public function createAndSend(array $data): UserInvitation
    {
        $this->ensureUserDoesNotExist($data['email']);

        $invitation = UserInvitation::generate(
            email: $data['email'],
            role: $data['role'],
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            message: $data['message'] ?? null,
        );

        SendInvitationEmailJob::dispatch($invitation);

        return $invitation;
    }

    private function ensureUserDoesNotExist(string $email): void
    {
        if (User::where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'A user with this email already exists.',
            ]);
        }
    }
}