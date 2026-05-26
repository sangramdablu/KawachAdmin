<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BillingAgreement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid', 
        'invoice_no', 
        'agreement_date', 
        'contract_start',
        'duration_months', 
        'status',
        'client_name',
        'client_contact',
        'client_email',
        'client_phone',
        'client_address',
        'client_name_enc', 
        'client_contact_enc', 
        'client_email_enc',
        'client_phone_enc', 
        'client_address_enc',
        'project_name', 
        'project_type', 
        'project_scope',
        'daily_hours', 
        'monthly_days', 
        'payment_cycle', 
        'payment_due_days',
        'late_fee_pct', 
        'advance_pct', 
        'tax_pct',
        'currency', 
        'currency_symbol', 
        'payment_methods',
        'subtotal_cents', 
        'tax_cents', 
        'monthly_total_cents',
        'advance_cents', 
        'grand_total_cents',
        'custom_clauses', 
        'company_signature', 
        'client_signature',
        'created_by', 
        'sent_at', 
        'signed_at',
    ];

    protected $casts = [
        'agreement_date'  => 'date',
        'contract_start'  => 'date',
        'sent_at'         => 'datetime',
        'signed_at'       => 'datetime',
    ];

    // ── Boot: auto UUID ───────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->uuid ??= (string) Str::uuid();
        });
    }

    // ── Relationships ─────────────────────────────────────────────────────
    public function developers()
    {
        return $this->hasMany(BillingAgreementDeveloper::class)
                    ->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Encrypted accessors / mutators ───────────────────────────────────
    // client_name
    public function getClientNameAttribute(): string
    {
        return $this->client_name_enc ? decrypt($this->client_name_enc) : '';
    }
    public function setClientNameAttribute(string $val): void
    {
        $this->attributes['client_name_enc'] = encrypt($val);
    }

    // client_contact
    public function getClientContactAttribute(): string
    {
        return $this->client_contact_enc ? decrypt($this->client_contact_enc) : '';
    }
    public function setClientContactAttribute(string $val): void
    {
        $this->attributes['client_contact_enc'] = encrypt($val);
    }

    // client_email
    public function getClientEmailAttribute(): string
    {
        return $this->client_email_enc ? decrypt($this->client_email_enc) : '';
    }
    public function setClientEmailAttribute(string $val): void
    {
        $this->attributes['client_email_enc'] = encrypt($val);
    }

    // client_phone
    public function getClientPhoneAttribute(): ?string
    {
        return $this->client_phone_enc ? decrypt($this->client_phone_enc) : null;
    }
    public function setClientPhoneAttribute(?string $val): void
    {
        $this->attributes['client_phone_enc'] = $val ? encrypt($val) : null;
    }

    // client_address
    public function getClientAddressAttribute(): ?string
    {
        return $this->client_address_enc ? decrypt($this->client_address_enc) : null;
    }
    public function setClientAddressAttribute(?string $val): void
    {
        $this->attributes['client_address_enc'] = $val ? encrypt($val) : null;
    }

    // ── Money helpers (cents → formatted) ────────────────────────────────
    public function formatMoney(int $cents): string
    {
        return $this->currency_symbol . number_format($cents / 100, 2);
    }

    public function getMonthlyTotalFormattedAttribute(): string
    {
        return $this->formatMoney($this->monthly_total_cents);
    }

    public function getGrandTotalFormattedAttribute(): string
    {
        return $this->formatMoney($this->grand_total_cents);
    }

    public function getAdvanceFormattedAttribute(): string
    {
        return $this->formatMoney($this->advance_cents);
    }
}