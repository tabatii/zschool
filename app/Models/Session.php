<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'starts_at_ramadan' => 'datetime',
        'ends_at_ramadan' => 'datetime',
        'is_ignored' => 'boolean',
        'is_attended' => 'boolean',
        'is_cancelled' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function exam(): HasOne
    {
        return $this->hasOne(Exam::class);
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)->using(SessionStudent::class)->withPivot('status', 'absence_reason', 'is_justified');
    }

    public function scopeForModel($query, $model)
    {
        return match (get_class($model)) {
            default => $query,
            Group::class => $query->where('group_id', $model->id),
            Teacher::class => $query->where('teacher_id', $model->id),
            Student::class => $query->whereHas('group', fn ($q) => $q->whereRelation('students', 'id', $model->id)),
            Guardian::class => $query->whereHas('group', fn ($q) => $q->whereRelation('students', 'id', tenant()->getKey())),
        };
    }
}
