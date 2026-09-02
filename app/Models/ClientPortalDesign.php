<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPortalDesign extends Model
{
    protected $fillable = [
        'client_portal_project_id',
        'title',
        'version',
        'image_path',
        'figma_url',
        'status',
        'uploaded_by',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'            => 'Pending Review',
            'approved'           => 'Approved',
            'changes_requested'  => 'Changes Requested',
            default              => ucfirst($this->status),
        };
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending'           => 'badge-review',
            'approved'          => 'badge-active',
            'changes_requested' => 'badge-onhold',
            default             => 'badge-inprogress',
        };
    }

    public function project()
    {
        return $this->belongsTo(ClientPortalProject::class, 'client_portal_project_id');
    }

    public function comments()
    {
        return $this->hasMany(ClientPortalDesignComment::class)->orderBy('created_at');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
