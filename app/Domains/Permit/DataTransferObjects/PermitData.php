<?php

declare(strict_types=1);

namespace App\Domains\Permit\DataTransferObjects;

use App\Domains\Permit\Enums\PermitType;
use App\Domains\Permit\Models\Permit;
use App\Domains\Permit\State\Permit\PermitState;
use App\Domains\User\Models\User;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class PermitData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $user_id,
        public readonly string $type,
        public readonly string $title,
        public readonly string $content,
        public readonly ?string $since,
        public readonly ?string $until,
        public readonly string|PermitState|null $state,
        public readonly string|Carbon|null $created_at,
        public readonly string|Carbon|null $updated_at,
        public readonly string|Lazy|null $state_label,
        public readonly string|Lazy|User|null $user,
        public readonly string|Lazy|null $type_label,
    ) {}

    public static function fromModel(Permit $model): self
    {
        return new self(
            id: $model->id,
            user_id: $model->user_id,
            type: $model->type,
            title: $model->title,
            content: $model->content,
            state: $model->state,
            since: $model->since,
            until: $model->until,
            created_at: $model->created_at,
            updated_at: $model->updated_at,
            state_label: Lazy::create(fn () => $model->state_label),
            user: Lazy::create(fn () => $model->user),
            type_label: Lazy::create(fn () => PermitType::fromValue($model->type)?->translated()),
        );
    }
}
