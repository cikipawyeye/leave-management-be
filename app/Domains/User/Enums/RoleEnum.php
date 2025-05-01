<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

use App\Support\Traits\EnumTrait;

enum RoleEnum: string
{
    use EnumTrait;

    case User = 'user';
    case Admin = 'admin';
    case Verificator = 'verificator';

    public function translated(): string
    {
        return match ($this->value) {
            self::User->value => 'Ordinary User',
            self::Verificator->value => 'Verificator',
            self::Admin->value => 'Admin',
            default => $this->name,
        };
    }
}
