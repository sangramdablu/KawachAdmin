<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPortalDesignComment extends Model
{
    protected $fillable = [
        'client_portal_design_id',
        'user_id',
        'body',
        'x_position',
        'y_position',
    ];

    public function design()
    {
        return $this->belongsTo(ClientPortalDesign::class, 'client_portal_design_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
