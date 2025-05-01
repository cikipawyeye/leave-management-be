<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;
use App\Support\Actions\Action;

class DeleteUserAction extends Action
{
    public function __construct(
        protected readonly int $userId,
    ) {}

    public function handle()
    {
        return User::where('id', $this->userId)->delete();
    }
}
