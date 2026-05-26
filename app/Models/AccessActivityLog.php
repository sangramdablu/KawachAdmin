<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessActivityLog extends Model
{
    protected $table = 'access_activity_logs';

    protected $fillable = [
        'actor_id',
        'actor_name',
        'type',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function actor()
    {
        return $this->belongsTo(\App\Models\User::class, 'actor_id');
    }

    /**
     * Log an access event.
     */
    public static function record(string $type, string $description, ?int $actorId = null, ?string $actorName = null): self
    {
        return static::create([
            'actor_id'   => $actorId   ?? auth()->id(),
            'actor_name' => $actorName ?? auth()->user()?->name,
            'type'       => $type,
            'description'=> $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Return time-ago string for the created_at timestamp.
     */
    public function getTimeAgoAttribute(): string
    {
        $diff = now()->diffInSeconds($this->created_at);
        if ($diff < 60)           return 'Just now';
        if ($diff < 3600)         return floor($diff / 60) . ' mins ago';
        if ($diff < 86400)        return floor($diff / 3600) . ' hrs ago';
        if ($diff < 172800)       return 'Yesterday';
        if ($diff < 604800)       return floor($diff / 86400) . ' days ago';
        if ($diff < 1209600)      return '1 week ago';
        return $this->created_at->format('d M Y');
    }
}