<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class TaskList extends Model
{
    protected $fillable = [
        'name',
        'position',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(TaskCard::class)->orderBy('position');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }
}
