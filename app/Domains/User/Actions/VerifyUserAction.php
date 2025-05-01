<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Exceptions\UserException;
use App\Domains\User\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;

class VerifyUserAction extends Action
{
    public function __construct(
        protected readonly User $model
    ) {}

    public function handle(): User
    {
        if (! $this->model->hasAnyRole(RoleEnum::User->value)) {
            throw UserException::cannotVerifyNonOrdinaryUserAccount();
        }

        $this->model->email_verified_at = now();

        DB::transaction(function () {
            $this->model->save();
        });

        return $this->model;
    }
}
