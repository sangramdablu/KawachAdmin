<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ClientPortalInvoice extends Model
{
    protected $fillable = [
        'client_user_id',
        'billing_agreement_id',
        'invoice_id',
        'invoice_date',
        'amount_cents',
        'currency_symbol',
        'status',
        'icon_bg',
        'icon_color',
    ];
 
    protected $casts = [
        'invoice_date' => 'date',
    ];
 
    public function getAmountFormattedAttribute(): string
    {
        return $this->currency_symbol . number_format($this->amount_cents / 100, 2);
    }
 
    public function getDateFormattedAttribute(): string
    {
        return $this->invoice_date?->format('M d, Y') ?? '—';
    }
 
    public function clientUser()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }
 
    public function billingAgreement()
    {
        return $this->belongsTo(BillingAgreement::class);
    }
}