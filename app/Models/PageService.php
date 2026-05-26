<?php

/* ============================================================
   FILE: app/Models/PageService.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class PageService extends Model
{
    protected $table = 'page_services';
 
    protected $fillable = [
        'page_id',
        'short_description',
        'content',
        'features',
        'process_steps',
        'price_from',
        'billing_cycle',
        'cta_url',
        'cta_text',
        'technologies',
    ];
 
    protected $casts = [
        'features'      => 'array',
        'process_steps' => 'array',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
 
    // ── Accessors ────────────────────────────────────────────
 
    /** Returns technologies as a clean array, regardless of storage format */
    public function getTechArrayAttribute(): array
    {
        if (!$this->technologies) return [];
        return array_filter(array_map('trim', explode(',', $this->technologies)));
    }
}
 