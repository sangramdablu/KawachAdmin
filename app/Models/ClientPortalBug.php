<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPortalBug extends Model
{
    protected $fillable = [
        'client_portal_project_id',
        'client_user_id',
        'title',
        'description',
        'steps_to_reproduce',
        'expected_result',
        'actual_result',
        'attachment_path',
        'device',
        'browser',
        'priority',
        'status',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'reported'           => 'Reported',
            'under_review'       => 'Under Review',
            'in_progress'        => 'In Progress',
            'fixed'              => 'Fixed',
            'ready_for_testing'  => 'Ready for Testing',
            'closed'             => 'Closed',
            default              => ucfirst($this->status),
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'reported'          => 'badge-onhold',
            'under_review'      => 'badge-review',
            'in_progress'       => 'badge-inprogress',
            'fixed'             => 'badge-active',
            'ready_for_testing' => 'badge-review',
            'closed'            => 'badge-completed',
            default             => 'badge-inprogress',
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
}
