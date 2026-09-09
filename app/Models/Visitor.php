<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'kawach_visitors';

    protected $fillable = [
        'visitor_uuid',
        'first_seen_at',
        'last_seen_at',
        'visit_count',
        'total_pageviews',
        'total_time_seconds',
        'entry_route',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'country',
        'device_type',
        'browser',
        'os',
        'ip_address',
        'is_bot',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at'  => 'datetime',
        'is_bot'        => 'boolean',
    ];

    public function pageviews()
    {
        return $this->hasMany(VisitorPageview::class);
    }
}
