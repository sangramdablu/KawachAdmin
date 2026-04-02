<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description'
    ];
    public function blogs() {
        return $this->hasMany(Blog::class);
    }
}
