<?php

declare(strict_types=1);

namespace App\Domains\User\Listeners;

use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Events\Registered;

class SendEmailVerificationNotification
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Registered $event)
    {
        /** @var \App\Domains\User\Models\User */
        $user = $event->user;

        if (($user->hasRole(RoleEnum::Admin->value) || $user->hasRole(RoleEnum::Verificator->value)) && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
    }
}
