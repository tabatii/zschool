<?php

namespace App\Traits;

use App\Models\Session;
use App\Models\Season;
use Carbon\Carbon;

trait SessionTime
{
    public function getStartsAtForSession(Session $session, Season $season)
    {
        if ($session->starts_at->between($season->ramadan_starts_at, $season->ramadan_ends_at)) {
            return $session->starts_at_ramadan;
        }
        return $session->starts_at;
    }

    public function getEndsAtForSession(Session $session, Season $season)
    {
        if ($session->starts_at->between($season->ramadan_starts_at, $season->ramadan_ends_at)) {
            return $session->ends_at_ramadan;
        }
        return $session->ends_at;
    }
}
