<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Tag;
use App\Models\BlogSeo;
use App\Models\BlogComment;
use App\Models\BlogLike;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'image_alt',
        'image_title',
        'image_caption',
        'meta_title',
        'meta_description',
        'focus_keyword',
        'category_id',
        'author_id',
        'status',
        'visibility',
        'post_password',
        'allow_comments',
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
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function seo() {
        return $this->hasOne(BlogSeo::class);
    }

    public function comments() {
        return $this->hasMany(BlogComment::class);
    }

    public function likes() {
        return $this->hasMany(BlogLike::class);
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

}
