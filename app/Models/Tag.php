<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];
    public function blogs() {
        return $this->belongsToMany(Blog::class);
    }

    public function news() {
        return $this->belongsToMany(News::class);
    }
}
