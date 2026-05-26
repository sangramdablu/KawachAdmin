<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Stores visual metadata (color, icon, description) for Spatie roles.
 * Spatie's roles table only has name + guard_name — this extends it.
 */
class RoleMeta extends Model
{
    protected $table = 'role_meta';

    protected $fillable = [
        'role_name',
        'color',
        'icon',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // Color hex map — matches blade CSS classes
    public static array $colorMap = [
        'superadmin' => ['hex' => '#6c2bd9', 'bg' => '#f0e8ff'],
        'admin'      => ['hex' => '#1a73e8', 'bg' => '#e8f1fd'],
        'editor'     => ['hex' => '#00c896', 'bg' => '#e0faf3'],
        'viewer'     => ['hex' => '#8a9bb5', 'bg' => '#eef2f9'],
        'custom'     => ['hex' => '#ffb830', 'bg' => '#fff8e6'],
    ];

    public function getColorHexAttribute(): string
    {
        return self::$colorMap[$this->color]['hex'] ?? '#8a9bb5';
    }

    public function getColorBgAttribute(): string
    {
        return self::$colorMap[$this->color]['bg'] ?? '#eef2f9';
    }
}