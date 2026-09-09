<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorPageview extends Model
{
    protected $table = 'kawach_visitor_pageviews';

    protected $fillable = [
        'visitor_id',
        'route_name',
        'path',
        'referrer',
        'time_spent_seconds',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
