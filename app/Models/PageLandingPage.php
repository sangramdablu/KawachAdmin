<?php

 
/* ============================================================
   FILE: app/Models/PageLandingPage.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class PageLandingPage extends Model
{
    protected $table = 'page_landing_pages';
 
    protected $fillable = [
        'page_id',
        'hero_headline',
        'hero_subheadline',
        'cta_primary_text',
        'cta_primary_url',
        'cta_secondary_text',
        'cta_secondary_url',
        'landing_content',
        'landing_stats',
    ];
 
    protected $casts = [
        'landing_stats' => 'array',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
 
    // ── Accessors ────────────────────────────────────────────
 
    /** Whether a secondary CTA is configured */
    public function getHasSecondaryCTAAttribute(): bool
    {
        return !empty($this->cta_secondary_text) && !empty($this->cta_secondary_url);
    }
 
    /** Safe getter — always returns array */
    public function getStatsAttribute(): array
    {
        return $this->landing_stats ?? [];
    }
}