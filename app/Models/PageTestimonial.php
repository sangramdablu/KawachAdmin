<?php


/* ============================================================
   FILE: app/Models/PageTestimonial.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class PageTestimonial extends Model
{
    protected $table = 'page_testimonials';
 
    protected $fillable = [
        'page_id',
        'testimonial_quote',
        'testimonial_name',
        'testimonial_role',
        'testimonial_industry',
        'testimonial_service',
        'testimonial_rating',
        'testimonial_video',
    ];
 
    protected $casts = [
        'testimonial_rating' => 'integer',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
 
    // ── Accessors ────────────────────────────────────────────
 
    /** Returns star HTML (★★★★☆) for display in views */
    public function getStarsHtmlAttribute(): string
    {
        $rating = $this->testimonial_rating ?? 5;
        $stars  = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
        return "<span class=\"stars\" aria-label=\"{$rating} out of 5\">{$stars}</span>";
    }
 
    public function getIsVideoAttribute(): bool
    {
        return !empty($this->testimonial_video);
    }
}
 