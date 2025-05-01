<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Domains\User\Constants\PermissionConstant as P;
use App\Domains\User\Enums\RoleEnum as R;

class PermissionAssignment
{
    /**
     * @return array<string, string[]>
     */
    public static function assignments(): array // NOSONAR
    {
        return [
            P::VIEW_HORIZON_DASHBOARD => [R::Admin],

            // User
            P::MANAGE_USERS => [
                R::Admin,
            ],
            P::BROWSE_USERS => [
                R::Admin,
            ],
            P::READ_USER => [
                R::Admin,
            ],
            P::EDIT_USER => [
                R::Admin,
            ],
            P::ADD_USER => [
                R::Admin,
            ],
            P::DELETE_USER => [
                R::Admin,
            ],

            // Ordinary User
            P::MANAGE_ORDINARY_USERS => [
                R::Verificator,
            ],
            P::BROWSE_ORDINARY_USERS => [
                R::Verificator,
            ],
            P::READ_ORDINARY_USER => [
                R::Verificator,
            ],
            P::VERIFY_ORDINARY_USER => [
                R::Verificator,
            ],

            P::MANAGE_PERMITS => [
                R::Admin,
                R::Verificator,
                R::User,
            ],
            P::BROWSE_PERMITS => [
                R::Admin,
                R::Verificator,
                R::User,
            ],
            P::READ_PERMIT => [
                R::Admin,
                R::Verificator,
                R::User,
            ],
            P::EDIT_PERMIT => [
                R::User,
            ],
            P::ADD_PERMIT => [
                R::User,
            ],
            P::DELETE_PERMIT => [
                R::User,
            ],
            P::SET_PERMIT_STATE_PENDING => [
                R::Verificator,
            ],
            P::SET_PERMIT_STATE_REVISION => [
                R::Verificator,
            ],
            P::SET_PERMIT_STATE_APPROVED => [
                R::Verificator,
            ],
            P::SET_PERMIT_STATE_REJECTED => [
                R::Verificator,
            ],
            P::SET_PERMIT_STATE_CANCELED => [
                R::User,
            ],

            P::MANAGE_PERMIT_REVIEWS => [
                R::Admin,
                R::Verificator,
                R::User,
            ],
            P::BROWSE_PERMIT_REVIEWS => [
                R::Admin,
                R::Verificator,
                R::User,
            ],
            P::READ_PERMIT_REVIEW => [
                R::Admin,
                R::Verificator,
                R::User,
            ],
            P::EDIT_PERMIT_REVIEW => [
                R::Verificator,
            ],
            P::ADD_PERMIT_REVIEW => [
                R::Verificator,
            ],
            P::DELETE_PERMIT_REVIEW => [
                R::Verificator,
            ],
        ];
    }

    public static function getPermissionsByRole(string $role): array
    {
        $permissions = [];

        foreach (self::assignments() as $permission => $roles) {
            if (in_array($role, array_map(fn (R $role) => $role->value, $roles), true)) {
                $permissions[] = $permission;
            }
        }

        return $permissions;
    }
}
