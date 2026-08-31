<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Tag;
use App\Models\NewsSeo;
use App\Models\User;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'image_alt',
        'image_title',
        'image_caption',
        'category_id',
        'author_id',
        'external_source_name',
        'external_source_url',
        'meta_title',
        'meta_description',
        'focus_keyword',
        'status',
        'visibility',
        'published_at',
        'views',
        'reading_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function seo()
    {
        return $this->hasOne(NewsSeo::class, 'news_id');
    }

    /** True when this item is coverage of the company by an outside publication. */
    public function getIsExternalAttribute(): bool
    {
        return !empty($this->external_source_name);
    }
}
