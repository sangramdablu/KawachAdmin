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

    public function invitedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public static function generate(string $email, string $role, ?string $firstName = null, ?string $lastName = null, ?string $message = null): self
    {
        // Delete any existing pending invite for this email
        static::where('email', $email)->whereNull('accepted_at')->delete();

        return static::create([
            'email'      => $email,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'role_name'  => $role,
            'message'    => $message,
            'token'      => Str::random(64),
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);
    }
}