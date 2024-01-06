<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function branchSubjects(): HasMany
    {
        return $this->hasMany(BranchSubject::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->using(BranchSubject::class)->withPivot('id', 'minutes', 'factor', 'year');
    }
}
