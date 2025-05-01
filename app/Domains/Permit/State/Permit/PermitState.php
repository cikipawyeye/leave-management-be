<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\Permit;

use App\Domains\User\Constants\PermissionConstant as Permission;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PermitState extends State
{
    abstract public static function stateLabel(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Approved::class)
            ->allowTransition(Pending::class, Rejected::class)
            ->allowTransition(Pending::class, Revision::class)
            ->allowTransition(Pending::class, Canceled::class)
            ->allowTransition(Revision::class, Pending::class)
            ->allowTransition(Revision::class, Approved::class)
            ->allowTransition(Revision::class, Rejected::class)
            ->allowTransition(Revision::class, Canceled::class);
    }

    public function transitionableStates(...$transitionArgs): array
    {
        return collect(parent::transitionableStates(...$transitionArgs))
            ->map(function (string $state) {
                return match ($state) {
                    Pending::$name => [
                        'state' => $state,
                        'permission' => Permission::SET_PERMIT_STATE_PENDING,
                        'label' => Pending::stateLabel(),
                    ],
                    Revision::$name => [
                        'state' => $state,
                        'permission' => Permission::SET_PERMIT_STATE_REVISION,
                        'label' => Revision::stateLabel(),
                    ],
                    Approved::$name => [
                        'state' => $state,
                        'permission' => Permission::SET_PERMIT_STATE_APPROVED,
                        'label' => Approved::stateLabel(),
                    ],
                    Rejected::$name => [
                        'state' => $state,
                        'permission' => Permission::SET_PERMIT_STATE_REJECTED,
                        'label' => Rejected::stateLabel(),
                    ],
                    Canceled::$name => [
                        'state' => $state,
                        'permission' => Permission::SET_PERMIT_STATE_CANCELED,
                        'label' => Canceled::stateLabel(),
                    ],
                };
            })
            ->toArray();
    }
}
