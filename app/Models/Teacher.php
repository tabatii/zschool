<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;

class Teacher extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'gender' => \App\Enums\Gender::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'datetime',
        'attachments' => 'array',
        'details' => 'json',
        'top_navigation' => 'boolean',
        'wide_content' => 'boolean',
    ];

    protected $attributes = [
        'avatar' => 'defaults/avatar.png',
        'panel_color' => 'sky',
        'top_navigation' => false,
        'wide_content' => false,
    ];

    protected function avatar(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => empty($value) ? 'defaults/avatar.png' : $value,
        );
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? asset("storage/{$this->avatar}") : null;
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }
}
