<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Exceptions\UserException;
use App\Domains\User\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;

class SetUserRoleAction extends Action
{
    public function __construct(
        protected readonly User $model,
        protected readonly RoleEnum $role
    ) {}

    public function handle(): User
    {
        if ($this->model->hasAnyRole(RoleEnum::Admin->value)) {
            throw UserException::cannotUpdateAdminToUser();
        }

        DB::transaction(function () {
            $this->model->syncRoles($this->role->value);
        });

        return $this->model;
    }
}
