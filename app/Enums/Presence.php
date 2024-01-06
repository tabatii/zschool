<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Presence: string implements HasLabel
{
    case PRESENT = 'P';
    case LATE = 'L';
    case ABSENT = 'A';

    public function getLabel(): ?string
    {
        return match($this)
        {
            self::PRESENT => __('Present'),
            self::LATE => __('Late'),
            self::ABSENT => __('Absent'),
        };
    }

    public function isPresent(): ?bool
    {
        return match($this)
        {
            default => false,
            self::PRESENT => true,
        };
    }

    public function isLate(): ?bool
    {
        return match($this)
        {
            default => false,
            self::LATE => true,
        };
    }

    public function isAbsent(bool $is_justified): ?bool
    {
        return match($this)
        {
            default => false,
            self::ABSENT => !$is_justified,
        };
    }

    public function isAJ(bool $is_justified): ?bool
    {
        return match($this)
        {
            default => false,
            self::ABSENT => $is_justified,
        };
    }
}
