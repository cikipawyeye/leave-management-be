<?php

declare(strict_types=1);

namespace App\Domains\Permit\Actions;

use App\Domains\Permit\DataTransferObjects\PermitData;
use App\Domains\Permit\Exceptions\PermitException;
use App\Domains\Permit\Models\Permit;
use App\Domains\Permit\State\Permit\Pending;
use App\Domains\Permit\State\Permit\Revision;
use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Models\User;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\DB;

class SavePermitAction extends Action
{
    public function __construct(
        protected readonly Permit $model,
        protected readonly PermitData $data,
        protected readonly User $user,
    ) {
        $this->validate();
    }

    public function handle(): Permit
    {
        $this->model->fill($this->data->only(
            'content',
            'title',
            'type',
            'since',
            'until',
        )->toArray());
        $this->model->user()->associate($this->user);

        DB::transaction(function () {
            if ($this->model->state instanceof Revision) {
                $this->model->state->transitionTo(Pending::class);
            }

            $this->model->save();
        });

        return $this->model;
    }

    protected function validate()
    {
        if (! $this->user->hasAnyRole(RoleEnum::User->value)) {
            throw PermitException::onlyOrdinaryUserCanModifyPermit();
        }

        if ((! $this->model->state instanceof Pending) && (! $this->model->state instanceof Revision)) {
            throw PermitException::permitAlreadyProcessed();
        }

        if (! empty($this->model->id) && $this->model->user_id != $this->user->id) {
            throw PermitException::notAllowedToModify();
        }
    }
}
