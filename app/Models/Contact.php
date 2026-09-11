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
    //  Safe display of encrypted fields
    // ──────────────────────────────────────────

    /**
     * Read an attribute that may be encrypted, without letting a decryption
     * failure blow up the whole page. Fails only when this app's APP_KEY
     * doesn't match the key the public site used to encrypt the row — in
     * which case align the two APP_KEY values (see the Contacts module notes).
     */
    public function safe(string $attr, string $fallback = '—'): string
    {
        try {
            $value = $this->getAttribute($attr);
        } catch (\Throwable) {
            return '🔒 encrypted — APP_KEY mismatch';
        }

        if ($value === null || $value === '' || $value === []) {
            return $fallback;
        }

        return is_array($value) ? implode(', ', $value) : (string) $value;
    }

    /**
     * Services list as an array, or an empty array if it can't be decrypted.
     */
    public function safeServices(): array
    {
        try {
            return (array) ($this->services ?? []);
        } catch (\Throwable) {
            return [];
        }
    }

    public function servicesReadable(): bool
    {
        try {
            $this->services;
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    // ──────────────────────────────────────────
    //  Status helpers
    // ──────────────────────────────────────────

    // status is intentionally not in $fillable — set it explicitly so a
    // mass-assignment guard change can't silently turn these into no-ops.

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function markAsRead(): void
    {
        $this->setStatus('read');
    }

    public function markAsReplied(): void
    {
        $this->setStatus('replied');
    }
}