<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogSeo extends Model
{
    protected $fillable = [
        'blog_id',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'canonical_url',
        'robots',
        'schema_type',
    ];

    public function blog() {
        return $this->belongsTo(Blog::class);
    }

}
