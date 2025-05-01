<?php

declare(strict_types=1);

namespace App\Domains\User\Exceptions;

use App\Support\Exceptions\AbstractException;

class UserException extends AbstractException
{
    public static function cannotCreateAdmin(): self
    {
        return new self('Invalid role.', 422);
    }

    public static function cannotUpdateAdminToUser(): self
    {
        return new self('You cannot update this user.', 422);
    }

    public static function cannotVerifyNonOrdinaryUserAccount(): self
    {
        return new self('You cannot verify this user.', 422);
    }
}
