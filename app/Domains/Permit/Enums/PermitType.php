<?php

declare(strict_types=1);

namespace App\Domains\Permit\Enums;

use App\Support\Traits\EnumTrait;

enum PermitType: string
{
    use EnumTrait;

    case Sick = 'sick';
    case Leave = 'leave';
    case Other = 'other';

    public function translated(): string
    {
        return match ($this->value) {
            self::Sick->value => 'Sick Leave',
            self::Leave->value => 'Vacation Leave',
            self::Other->value => 'Other',
            default => $this->name,
        };
    }
}
