<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\Permit;

class Pending extends PermitState
{
    public static string $name = 'pending';

    public static function stateLabel(): string
    {
        return __('app.pending');
    }
}
