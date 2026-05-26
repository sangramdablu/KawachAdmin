<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingAgreementDeveloper extends Model
{
    protected $fillable = [
        'billing_agreement_id', 'developer_label', 'technology',
        'tech_icon', 'hourly_rate_cents', 'quantity',
        'monthly_hours', 'monthly_cost_cents', 'sort_order',
    ];

    public function agreement()
    {
        return $this->belongsTo(BillingAgreement::class, 'billing_agreement_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    public function getHourlyRateAttribute(): float
    {
        return $this->hourly_rate_cents / 100;
    }

    public function getMonthlyCostAttribute(): float
    {
        return $this->monthly_cost_cents / 100;
    }

    public function getMonthlyCostFormattedAttribute(): string
    {
        $sym = $this->agreement?->currency_symbol ?? '$';
        return $sym . number_format($this->monthly_cost_cents / 100, 2);
    }
}