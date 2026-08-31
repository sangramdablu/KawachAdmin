<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_id',
        'name',
        'email',
        'comment',
        'status',
        'ip_address',
        'user_agent',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Only comments that have been approved by an admin — the only ones
     * allowed to render on the public site.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
