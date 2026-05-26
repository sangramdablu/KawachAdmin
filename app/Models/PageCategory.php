<?php
/* ============================================================
   FILE: app/Models/PageCategory.php
   ============================================================ */
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
class PageCategory extends Model
{
    protected $table = 'page_categories';
 
    protected $fillable = [
        'name',
        'slug',
        'page_type',
        'sort_order',
    ];
 
    protected $casts = [
        'sort_order' => 'integer',
    ];
 
    // ── Relationships ────────────────────────────────────────
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'category_id');
    }
 
    // ── Scopes ───────────────────────────────────────────────
    public function scopeForType($query, string $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->where('page_type', $type)->orWhereNull('page_type');
        });
    }
}
 