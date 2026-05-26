<?php

 
/* ============================================================
   FILE: app/Models/PageCaseStudy.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class PageCaseStudy extends Model
{
    protected $table = 'page_case_studies';
 
    protected $fillable = [
        'page_id',
        'client_name',
        'client_industry',
        'project_duration',
        'completion_date',
        'project_url',
        'challenge',
        'solution',
        'kpis',
        'technologies',
        'testimonial_quote',
        'testimonial_name',
        'testimonial_role',
    ];
 
    protected $casts = [
        'kpis' => 'array',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
 
    // ── Accessors ────────────────────────────────────────────
    public function getTechArrayAttribute(): array
    {
        if (!$this->technologies) return [];
        return array_filter(array_map('trim', explode(',', $this->technologies)));
    }
 
    public function getHasTestimonialAttribute(): bool
    {
        return !empty($this->testimonial_quote) && !empty($this->testimonial_name);
    }
}