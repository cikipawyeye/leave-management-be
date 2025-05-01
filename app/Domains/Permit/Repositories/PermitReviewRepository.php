<?php

declare(strict_types=1);

namespace App\Domains\Permit\Repositories;

use App\Support\Repositories\Repository;

class PermitReviewRepository extends Repository
{
    /** @var array<string>|null */
    protected ?array $searchableColumns = ['comment'];

    /** @var array<string>|null */
    protected ?array $sortableColumns = ['created_at'];

    protected string $model = \App\Domains\Permit\Models\PermitReview::class;

    /** @var array<string>|null */
    protected ?array $with = ['reviewer'];

    protected function sort($value): static
    {
        if ('latest' == $value) {
            $this->defaultSort = 'created_at';
            $this->defaultSortDirection = self::SORT_DIRECTION_DESC;
        } elseif ('oldest' == $value) {
            $this->defaultSort = 'created_at';
            $this->defaultSortDirection = self::SORT_DIRECTION_ASC;
        }

        return $this;
    }

    protected function permit(?int $permitId): static
    {
        if (! empty($permitId)) {
            $this->query->where('permit_id', $permitId);
        }

        return $this;
    }

    protected function state(?string $value): static
    {
        if (! empty($value) && in_array($value, [
            \App\Domains\Permit\State\PermitReview\Revision::$name,
            \App\Domains\Permit\State\PermitReview\Approved::$name,
            \App\Domains\Permit\State\PermitReview\Rejected::$name,
        ])) {
            $this->query->where('state', $value);
        }

        return $this;
    }
}
