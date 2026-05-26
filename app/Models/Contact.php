<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;

class Contact extends Model
{
    /**
     * Only the fields the controller is allowed to mass-assign.
     * Everything else (id, ip_address, status, timestamps) is set explicitly.
     */
    protected $fillable = [
        'full_name',
        'company',
        'email',
        'phone',
        'subject',
        'services',
        'budget',
        'message',
    ];

    /**
     * Cast sensitive fields to encrypted values at the Eloquent layer.
     * Requires APP_KEY to be set. Values are transparent in PHP but
     * stored as ciphertext in the database.
     *
     * - email         → encrypted string  (searchable via manual hash if needed)
     * - phone         → encrypted string
     * - message       → encrypted string
     * - services      → encrypted JSON array
     * - ip_address    → encrypted string
     */
    protected $casts = [
        'email'      => 'encrypted',
        'phone'      => 'encrypted',
        'message'    => 'encrypted',
        'services'   => 'encrypted:array',
        'ip_address' => 'encrypted',
    ];

    /**
     * Never expose these fields in API responses / toArray().
     */
    protected $hidden = [
        'ip_address',
    ];

    // ──────────────────────────────────────────
    //  Status helpers
    // ──────────────────────────────────────────

    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }

    public function markAsReplied(): void
    {
        $this->update(['status' => 'replied']);
    }
}