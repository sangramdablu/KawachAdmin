<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogLike extends Model
{
    protected $table = 'blog_likes';

    protected $fillable = [
        'blog_id',
        'ip_address',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
