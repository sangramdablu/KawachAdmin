<?php


/* ============================================================
   FILE: app/Models/PageBlog.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
 
class PageBlog extends Model
{
    protected $table = 'page_blogs';
 
    protected $fillable = [
        'page_id',
        'blog_content',
        'excerpt',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
 
    // ── Accessors ────────────────────────────────────────────
 
    /** Word count for read-time display */
    public function getWordCountAttribute(): int
    {
        return str_word_count(strip_tags($this->blog_content ?? ''));
    }
 
    /** Estimated read time in minutes */
    public function getReadTimeAttribute(): int
    {
        return max(1, (int) ceil($this->word_count / 200));
    }
 
    /** Strip HTML tags for plain text excerpt fallback */
    public function getPlainExcerptAttribute(): string
    {
        if ($this->excerpt) return $this->excerpt;
        return Str::limit(strip_tags($this->blog_content ?? ''), 160);
    }
}
 