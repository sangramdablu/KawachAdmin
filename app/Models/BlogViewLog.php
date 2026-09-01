<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogViewLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blog_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
