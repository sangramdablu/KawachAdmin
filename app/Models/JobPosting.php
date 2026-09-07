<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    /**
     * Countries a job can be targeted at — ISO 3166-1 alpha-2 (lowercase),
     * matching flag-icons classes and the areaServed list already used in
     * the public site's Organization schema. An empty `countries` array on
     * a job means Global — visible everywhere, no country bias.
     */
    public const COUNTRIES = [
        'in' => 'India',
        'us' => 'United States',
        'gb' => 'United Kingdom',
        'de' => 'Germany',
        'fr' => 'France',
        'nl' => 'Netherlands',
        'es' => 'Spain',
        'it' => 'Italy',
        'au' => 'Australia',
    ];

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'countries',
        'type',
        'experience_level',
        'openings',
        'salary_range',
        'application_deadline',
        'summary',
        'responsibilities',
        'requirements',
        'nice_to_have',
        'status',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'responsibilities'      => 'array',
        'requirements'          => 'array',
        'nice_to_have'          => 'array',
        'countries'             => 'array',
        'application_deadline'  => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_slug', 'slug');
    }
}
