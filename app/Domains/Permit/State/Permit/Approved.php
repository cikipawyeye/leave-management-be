<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\Permit;

class Approved extends PermitState
{
    public static string $name = 'approved';

    public static function stateLabel(): string
    {
        return __('app.approved');
    }
}
