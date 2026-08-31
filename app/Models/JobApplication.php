<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_title',
        'job_slug',
        'full_name',
        'email',
        'phone',
        'experience',
        'linkedin_url',
        'portfolio_url',
        'cover_letter',
        'resume_path',
        'resume_original_name',
        'status',
        'ip_address',
        'user_agent',
    ];
}
