<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\DataTransferObjects\UserData;
use App\Domains\User\Events\Registered;
use App\Domains\User\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreUserAction extends Action
{
    public function __construct(
        protected readonly UserData $data,
        protected readonly bool $shouldVerifyEmail = false,
    ) {}

    public function handle(): User
    {
        $model = new User;
        $model->fill($this->data->only(
            'name',
            'email',
        )->toArray());

        if (! $this->shouldVerifyEmail) {
            $model->forceFill([
                'email_verified_at' => now(),
            ]);
        }

        $model->password = Hash::make($this->data->password);

        DB::transaction(function () use ($model) {
            $model->save();
            $model->syncRoles($this->data->role);
        });

        event(new Registered($model, $this->shouldVerifyEmail));

        return $model;
    }
}
