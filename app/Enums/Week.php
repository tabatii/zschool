<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Carbon\Carbon;

enum Week: string implements HasLabel
{
    case ONE = '1';
    case TWO = '2';
    case THREE = '3';
    case FOUR = '4';

    public function getLabel(): ?string
    {
        return match($this)
        {
            self::ONE => "{$this->value} ".trans_choice('Week|Weeks', $this->value),
            self::TWO => "{$this->value} ".trans_choice('Week|Weeks', $this->value),
            self::THREE => "{$this->value} ".trans_choice('Week|Weeks', $this->value),
            self::FOUR => "{$this->value} ".trans_choice('Week|Weeks', $this->value),
        };
    }
}
