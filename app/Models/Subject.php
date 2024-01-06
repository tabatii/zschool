<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class)->using(BranchSubject::class)->withPivot('id', 'minutes', 'factor', 'year');
    }
}
