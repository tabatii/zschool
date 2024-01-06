<?php

namespace App\Traits;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Carbon\Carbon;

trait CalendarControls
{
    #[Locked]
    public Carbon $now;

    #[Locked]
    public Carbon $startOfWeek;

    #[Locked]
    public Carbon $endOfWeek;

    public string $week;

    #[Computed]
    public function dates()
    {
        return Carbon::parse($this->startOfWeek)->daysUntil($this->endOfWeek);
    }

    public function updatedWeek($value)
    {
        $this->now = Carbon::parse($value);
        $this->setWeekRange();
    }

    public function addWeek()
    {
        $this->now->addWeek();
        $this->week = $this->now->format('Y-\\WW');
        $this->setWeekRange();
    }

    public function subWeek()
    {
        $this->now->subWeek();
        $this->week = $this->now->format('Y-\\WW');
        $this->setWeekRange();
    }

    public function setDate(string $date)
    {
        $this->now = Carbon::parse($date);
        $this->week = $this->now->format('Y-\\WW');
        $this->setWeekRange();
    }

    public function setWeekRange()
    {
        $this->startOfWeek = $this->now->copy()->startOfWeek(Carbon::MONDAY);
        $this->endOfWeek = $this->now->copy()->endOfWeek(Carbon::SUNDAY);
    }
}
