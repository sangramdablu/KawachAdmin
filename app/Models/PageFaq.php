<?php


/* ============================================================
   FILE: app/Models/PageFaq.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class PageFaq extends Model
{
    protected $table = 'page_faqs';
 
    protected $fillable = [
        'page_id',
        'faq_category',
        'faq_order',
        'faq_items',
    ];
 
    protected $casts = [
        'faq_order'  => 'integer',
        'faq_items'  => 'array',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
 
    // ── Accessors ────────────────────────────────────────────
 
    /** Safe getter — always returns array even if JSON is null */
    public function getItemsAttribute(): array
    {
        return $this->faq_items ?? [];
    }
 
    /** Total number of Q&A pairs */
    public function getCountAttribute(): int
    {
        return count($this->faq_items ?? []);
    }
}
 