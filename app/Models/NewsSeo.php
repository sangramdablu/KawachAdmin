<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSeo extends Model
{
    protected $table = 'news_seos';

    protected $fillable = [
        'news_id',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'canonical_url',
        'robots',
        'schema_type',
        'meta_keywords',
        'schema_author',
        'schema_rating_value',
        'schema_rating_count',
        'twitter_card',
        'twitter_creator',
        'hreflang',
        'sitemap_priority',
        'sitemap_changefreq',
        'custom_head_scripts',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
