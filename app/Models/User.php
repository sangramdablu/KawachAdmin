<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'last_login_at',
        'is_team_member',
        'designation',
        'team_role',
        'responsibilities',
        'bio',
        'linkedin_url',
        'years_experience',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at'     => 'datetime',
            'is_team_member'    => 'boolean',
            'years_experience'  => 'integer',
        ];
    }

    public function clientPortalProjects(): HasMany
    {
        return $this->hasMany(ClientPortalProject::class, 'client_user_id');
    }

    /**
     * Content authored by this user. Content stays linked by author_id;
     * the displayed name/designation are always read live from this
     * relationship rather than snapshotted at creation time.
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function newsArticles(): HasMany
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /**
     * Absolute URL to the user's uploaded avatar, or null when none is set —
     * callers (RoleAccessController::formatUser(), the Team page, etc.)
     * fall back to the generated colored-initials avatar when this is null.
     * Avatars are uploaded via ImageUploadService::uploadToPublic(), which
     * stores paths relative to public/ (same convention as Blog/News images).
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset($this->avatar) : null;
    }

    /**
     * Deterministic colour palette used for generated-initials avatars.
     * Kept identical to the arrays already duplicated in
     * RoleAccessController::formatUser() and TeamController::index() so
     * the same user always gets the same colour everywhere in the app.
     * New call sites (topbar dropdown, My Profile page) should use the
     * getInitialsAttribute()/getAvatarColorAttribute() accessors below
     * rather than re-copying this logic a third/fourth time.
     */
    private const AVATAR_COLORS = ['#6c2bd9', '#1a73e8', '#00c896', '#e91e8c', '#ff6d00', '#00bcd4', '#673ab7', '#f44336', '#4caf50', '#ff9800'];

    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', trim($this->name ?? ''));
        return strtoupper(substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }

    public function getAvatarColorAttribute(): string
    {
        return self::AVATAR_COLORS[$this->id % count(self::AVATAR_COLORS)];
    }
}
