<?php

namespace App\Models;

 
use Illuminate\Database\Eloquent\Model;
 
class ClientPortalTeam extends Model
{
    protected $table = 'client_portal_team';
    protected $fillable = [
        'client_portal_project_id',
        'name',
        'role',
        'initials',
        'color',
        'status',
        'sort_order',
    ];
 
    public function project()
    {
        return $this->belongsTo(ClientPortalProject::class, 'client_portal_project_id');
    }
}
 