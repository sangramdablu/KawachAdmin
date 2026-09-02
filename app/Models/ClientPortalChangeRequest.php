<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPortalChangeRequest extends Model
{
    protected $fillable = [
        'client_portal_project_id',
        'client_user_id',
        'title',
        'description',
        'priority',
        'screenshot_path',
        'expected_result',
        'estimated_hours',
        'additional_cost',
        'deadline_impact',
        'responded_by',
        'responded_at',
        'response_note',
        'client_note',
        'status',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted'                => 'Submitted',
            'responded'                => 'Awaiting Your Decision',
            'approved'                 => 'Approved',
            'rejected'                 => 'Rejected',
            'clarification_requested'  => 'Clarification Requested',
            default                    => ucfirst($this->status),
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'submitted'               => 'badge-inprogress',
            'responded'               => 'badge-review',
            'approved'                => 'badge-active',
            'rejected'                => 'badge-onhold',
            'clarification_requested' => 'badge-onhold',
            default                   => 'badge-inprogress',
        };
    }

    public function project()
    {
        return $this->belongsTo(ClientPortalProject::class, 'client_portal_project_id');
    }

    public function clientUser()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}
