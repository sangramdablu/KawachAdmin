<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserInvitation extends Model
{
    protected $table = 'user_invitations';

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'role_name',
        'message',
        'token',
        'invited_by',
        'accepted_at',
        'expires_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function invitedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'invited_by');
    }

    // ── State helpers ──────────────────────────────────────────

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function isUsable(): bool
    {
        return ! $this->isAccepted() && ! $this->isExpired();
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // ── Factory method ─────────────────────────────────────────

    /**
     * Generate a new invitation.
     *
     * TOKEN STRATEGY:
     * The raw random token is stored directly in the `token` column.
     * The same raw value is put in the invitation URL.
     * InvitationController::findValidInvitation() queries by the raw token.
     *
     * If you ever need a hash-based approach (token in URL ≠ token in DB),
     * that change must be made consistently in BOTH this method AND
     * InvitationController::findValidInvitation() at the same time.
     */
    public static function generate(
        string  $email,
        string  $role,
        ?string $firstName = null,
        ?string $lastName  = null,
        ?string $message   = null,
    ): self {
        // Delete any existing pending (non-accepted) invite for this email
        // so we never have two valid tokens for the same address.
        static::where('email', $email)
               ->whereNull('accepted_at')
               ->delete();

        return static::create([
            'email'      => strtolower(trim($email)),
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'role_name'  => $role,
            'message'    => $message,
            'token'      => Str::random(64),   // raw token stored as-is
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);
    }
}