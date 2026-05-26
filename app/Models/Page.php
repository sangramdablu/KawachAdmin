<?php

// ============================================================
//  FILE: app/Models/Page.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'pages';

    protected $fillable = [
        'page_type',
        'title',
        'slug',
        'status',
        'visibility',
        'page_password',
        'is_featured',
        'sort_order',
        'category_id',
        'author_id',
        'published_at',
        'featured_image',
        'image_alt',
        'image_title',
        'focus_keyword',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'schema_type',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'hreflang',
        'sitemap_priority',
        'sitemap_changefreq',
        'custom_head_script',
        'tags',
    ];

    protected $casts = [
        'is_featured'      => 'boolean',
        'sort_order'       => 'integer',
        'published_at'     => 'datetime',
        'deleted_at'       => 'datetime',
        'sitemap_priority' => 'float',
    ];

    // ── Scopes ────────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('page_type', $type);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ── Relationships ─────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(PageCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Type-specific child relationships
    public function service(): HasOne
    {
        return $this->hasOne(PageService::class);
    }

    public function caseStudy(): HasOne
    {
        return $this->hasOne(PageCaseStudy::class);
    }

    public function teamMember(): HasOne
    {
        return $this->hasOne(PageTeamMember::class);
    }

    public function testimonial(): HasOne
    {
        return $this->hasOne(PageTestimonial::class);
    }

    public function faq(): HasOne
    {
        return $this->hasOne(PageFaq::class);
    }

    public function portfolio(): HasOne
    {
        return $this->hasOne(PagePortfolio::class);
    }

    public function blog(): HasOne
    {
        return $this->hasOne(PageBlog::class);
    }

    public function landingPage(): HasOne
    {
        return $this->hasOne(PageLandingPage::class);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getTypeDataAttribute(): ?Model
    {
        return match ($this->page_type) {
            'service'     => $this->service,
            'casestudy'   => $this->caseStudy,
            'team'        => $this->teamMember,
            'testimonial' => $this->testimonial,
            'faq'         => $this->faq,
            'portfolio'   => $this->portfolio,
            'blog'        => $this->blog,
            'landing'     => $this->landingPage,
            default       => null,
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->page_type) {
            'service'     => 'Service',
            'casestudy'   => 'Case Study',
            'team'        => 'Team Member',
            'testimonial' => 'Testimonial',
            'faq'         => 'FAQ',
            'portfolio'   => 'Portfolio',
            'blog'        => 'Blog Post',
            'landing'     => 'Landing Page',
            default       => ucfirst($this->page_type),
        };
    }

    /**
     * FIX: Images are uploaded directly to /public/{path} by ImageUploadService::uploadToPublic().
     * The stored path is relative to /public/, e.g. "page_images/service/filename.jpg".
     * Use asset() directly — NOT asset('storage/...') — to build the URL.
     *
     * If your project uses storage:link and stores under storage/app/public instead,
     * change this to: return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset($this->featured_image) : null;
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }
}