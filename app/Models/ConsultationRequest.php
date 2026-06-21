<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'role',
        'requirements',
        'goals',
        'ip_address',
        'user_agent',
        'status'
    ];
}
