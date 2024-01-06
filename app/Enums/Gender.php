<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum Gender: string implements HasLabel, HasColor
{
    case MALE = 'male';
    case FEMALE = 'female';

    public function getLabel(): ?string
    {
        return match($this)
        {
            self::MALE => __('Male'),
            self::FEMALE => __('Female'),
        };
    }

    public function getColor(): string
    {
        return match($this)
        {
            self::MALE => 'success',
            self::FEMALE => 'warning',
        };
    }
}
