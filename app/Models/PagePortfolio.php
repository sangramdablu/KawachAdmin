<?php

/* ============================================================
   FILE: app/Models/PagePortfolio.php
   ============================================================ */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagePortfolio extends Model
{
    protected $table = 'page_portfolio';

    protected $fillable = [
        'page_id',
        'portfolio_desc',
        'portfolio_category',
        'portfolio_year',
        'portfolio_url',
        'portfolio_tech',
        'portfolio_content',
        'gallery',
    ];

    protected $casts = [
        'portfolio_year' => 'integer',
        'gallery'        => 'array',
    ];

    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    // ── Accessors ────────────────────────────────────────────
    public function getTechArrayAttribute(): array
    {
        if (!$this->portfolio_tech) return [];
        return array_filter(array_map('trim', explode(',', $this->portfolio_tech)));
    }

    /**
     * FIX: Gallery images are stored via ImageUploadService::uploadToPublic()
     * which saves directly to /public/{path}. Path stored is relative to /public/,
     * e.g. "page_images/portfolio/gallery/filename.jpg".
     * Use asset() directly — NOT asset('storage/...').
     *
     * If you use storage:link, change to: asset('storage/' . $p)
     */
    public function getGalleryUrlsAttribute(): array
    {
        $paths = $this->gallery ?? [];
        return array_map(fn($p) => asset($p), $paths);
    }
}