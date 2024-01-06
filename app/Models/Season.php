<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'ramadan_starts_at' => 'datetime',
        'ramadan_ends_at' => 'datetime',
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }
}
