<?php


/* ============================================================
   FILE: app/Models/PageTeamMember.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class PageTeamMember extends Model
{
    protected $table = 'page_team_members';
 
    protected $fillable = [
        'page_id',
        'job_title',
        'department',
        'member_email',
        'member_phone',
        'member_location',
        'bio',
        'skills',
        'social_linkedin',
        'social_twitter',
        'social_github',
        'social_website',
    ];
 
    protected $casts = [
        'skills' => 'array',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
 
    // ── Accessors ────────────────────────────────────────────
 
    /** Returns only social links that have a value */
    public function getActiveSocialsAttribute(): array
    {
        $socials = [];
        if ($this->social_linkedin) $socials['LinkedIn']  = ['url' => $this->social_linkedin, 'icon' => 'fab fa-linkedin'];
        if ($this->social_twitter)  $socials['Twitter']   = ['url' => $this->social_twitter,  'icon' => 'fab fa-twitter'];
        if ($this->social_github)   $socials['GitHub']    = ['url' => $this->social_github,   'icon' => 'fab fa-github'];
        if ($this->social_website)  $socials['Website']   = ['url' => $this->social_website,  'icon' => 'fas fa-globe'];
        return $socials;
    }
}
 