<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ClientPortalTask extends Model
{
    protected $fillable = [
        'client_portal_project_id',
        'name',
        'state',
        'priority',
        'due',
        'overdue',
    ];
 
    protected $casts = [
        'due'     => 'date',
        'overdue' => 'boolean',
    ];
 
    public function getDueFormattedAttribute(): string
    {
        return $this->due?->format('M d, Y') ?? '—';
    }
 
    public function project()
    {
        return $this->belongsTo(ClientPortalProject::class, 'client_portal_project_id');
    }
}
 
