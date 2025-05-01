<?php

declare(strict_types=1);

namespace App\Domains\Permit\Actions;

use App\Domains\Permit\DataTransferObjects\PermitReviewData;
use App\Domains\Permit\Exceptions\PermitException;
use App\Domains\Permit\Models\PermitReview;
use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;

class StorePermitReviewAction extends Action
{
    public function __construct(
        protected readonly PermitReview $model,
        protected readonly PermitReviewData $data,
        protected readonly User $user,
    ) {
        $this->validate();
    }

    public function handle(): PermitReview
    {
        $this->model->fill($this->data->only(
            'permit_id',
            'comment',
        )->toArray());
        $this->model->reviewer()->associate($this->user);

        DB::transaction(function () {
            $this->model->state = $this->data->state;

            $this->model->save();

            $this->model->permit->state->transitionTo($this->data->state);
        });

        return $this->model;
    }

    protected function validate()
    {
        if (! $this->user->hasAnyRole(RoleEnum::Verificator->value)) {
            throw PermitException::onlyOrdinaryUserCanModifyPermit();
        }
    }
}
