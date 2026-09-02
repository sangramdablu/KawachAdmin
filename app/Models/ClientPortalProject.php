<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPortalProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_agreement_id',
        'client_user_id',
        'project_name',
        'project_type',
        'icon',
        'color_bg',
        'color',
        'status',
        'progress',
        'phases',
        'tags',
        'start_date',
        'deadline',
        'done_tasks',
        'total_tasks',
    ];

    protected $casts = [
        'phases'     => 'array',
        'tags'       => 'array',
        'start_date' => 'date',
        'deadline'   => 'date',
    ];

    // ── Status badge CSS class map ────────────────────────────────────────
    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active'     => 'badge-active',
            'inprogress' => 'badge-inprogress',
            'review'     => 'badge-review',
            'onhold'     => 'badge-onhold',
            'completed'  => 'badge-completed',
            default      => 'badge-inprogress',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'     => 'Active',
            'inprogress' => 'In Progress',
            'review'     => 'In Review',
            'onhold'     => 'On Hold',
            'completed'  => 'Completed',
            default      => ucfirst($this->status),
        };
    }

    // ── Formatted dates for Blade ─────────────────────────────────────────
    public function getStartDateFormattedAttribute(): string
    {
        return $this->start_date?->format('M d, Y') ?? '—';
    }

    public function getDeadlineFormattedAttribute(): string
    {
        return $this->deadline?->format('M d, Y') ?? '—';
    }

    // ── Relationships ─────────────────────────────────────────────────────
    public function billingAgreement()
    {
        return $this->belongsTo(BillingAgreement::class);
    }

    public function clientUser()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function tasks()
    {
        return $this->hasMany(ClientPortalTask::class);
    }

    public function team()
    {
        return $this->hasMany(ClientPortalTeam::class)->orderBy('sort_order');
    }

    public function designs()
    {
        return $this->hasMany(ClientPortalDesign::class);
    }

    public function changeRequests()
    {
        return $this->hasMany(ClientPortalChangeRequest::class);
    }

    public function bugs()
    {
        return $this->hasMany(ClientPortalBug::class);
    }
}