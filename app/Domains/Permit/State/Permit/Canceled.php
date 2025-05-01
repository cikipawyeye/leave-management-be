<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\Permit;

class Canceled extends PermitState
{
    public static string $name = 'canceled';

    public static function stateLabel(): string
    {
        return __('app.canceled');
    }
}
