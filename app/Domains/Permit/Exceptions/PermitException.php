<?php

declare(strict_types=1);

namespace App\Domains\Permit\Exceptions;

use App\Support\Exceptions\AbstractException;

class PermitException extends AbstractException
{
    public static function onlyOrdinaryUserCanModifyPermit(): self
    {
        return new self('Only ordinary users can modify a permit.', 422);
    }

    public static function permitAlreadyProcessed(): self
    {
        return new self('Permit already processed.', 422);
    }

    public static function notAllowedToModify(): self
    {
        return new self('You are not allowed to modify this permit.', 403);
    }

    public static function notAllowedToDelete(): self
    {
        return new self('You are not allowed to delete this permit.', 403);
    }
}
