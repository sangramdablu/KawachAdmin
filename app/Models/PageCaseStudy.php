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
        'business_size', 
        'location', 
        'business_model',
        'project_duration', 
        'completion_date', 
        'project_url',
        'challenge', 
        'existing_challenges',
        'solution', 
        'goals', 
        'solution_modules',
        'kpis', 
        'technologies', 
        'tech_stack',
        'cs_process_steps',
        'achievements', 
        'before_after', 
        'compliance_items', 
        'gallery',
        'testimonial_quote', 
        'testimonial_name', 
        'testimonial_role',
        'cs_features',
        'cs_faqs',
    ];

    protected $casts = [
        'kpis'                => 'array',
        'existing_challenges' => 'array',
        'goals'               => 'array',
        'solution_modules'    => 'array',
        'tech_stack'          => 'array',
        'cs_process_steps'    => 'array',
        'achievements'        => 'array',
        'before_after'        => 'array',
        'compliance_items'    => 'array',
        'gallery'             => 'array',
        'cs_features'         => 'array',
        'cs_faqs'             => 'array',
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