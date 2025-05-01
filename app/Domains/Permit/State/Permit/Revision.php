<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\Permit;

class Revision extends PermitState
{
    public static string $name = 'revision';

    public static function stateLabel(): string
    {
        return __('app.revision');
    }
}
