<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SessionStudent extends Pivot
{
    public $timestamps = false;

    protected $casts = [
        'status' => \App\Enums\Presence::class,
        'is_justified' => 'boolean',
    ];
}
