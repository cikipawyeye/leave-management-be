<?php

declare(strict_types=1);

namespace App\Domains\Permit\DataTransferObjects;

use App\Domains\Permit\Models\PermitReview;
use App\Domains\Permit\State\PermitReview\PermitReviewState;
use App\Domains\User\Models\User;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class PermitReviewData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $permit_id,
        public readonly ?int $reviewer_id,
        public readonly ?string $comment,
        public readonly string|PermitReviewState|null $state,
        public readonly string|Carbon|null $created_at,
        public readonly string|Lazy|null $state_label,
        public readonly string|Lazy|User|null $reviewer,
    ) {}

    public static function fromModel(PermitReview $model): self
    {
        return new self(
            id: $model->id,
            permit_id: $model->permit_id,
            reviewer_id: $model->reviewer_id,
            comment: $model->comment,
            state: $model->state,
            created_at: $model->created_at,
            state_label: Lazy::create(fn () => $model->state_label),
            reviewer: Lazy::create(fn () => $model->reviewer),
        );
    }
}
