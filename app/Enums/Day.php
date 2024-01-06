<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Carbon\Carbon;

enum Day: string implements HasLabel
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public function getLabel(): ?string
    {
        return match($this)
        {
            self::MONDAY => ucfirst(Carbon::parse($this->value)->dayName),
            self::TUESDAY => ucfirst(Carbon::parse($this->value)->dayName),
            self::WEDNESDAY => ucfirst(Carbon::parse($this->value)->dayName),
            self::THURSDAY => ucfirst(Carbon::parse($this->value)->dayName),
            self::FRIDAY => ucfirst(Carbon::parse($this->value)->dayName),
            self::SATURDAY => ucfirst(Carbon::parse($this->value)->dayName),
            self::SUNDAY => ucfirst(Carbon::parse($this->value)->dayName),
        };
    }
}
