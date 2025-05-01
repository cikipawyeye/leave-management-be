<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\Permit;

class Rejected extends PermitState
{
    public static string $name = 'rejected';

    public static function stateLabel(): string
    {
        return __('app.rejected');
    }
}
