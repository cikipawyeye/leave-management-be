<?php

declare(strict_types=1);

namespace App\Domains\Permit\Repositories;

use App\Domains\Permit\Enums\PermitType;
use App\Support\Repositories\Repository;

class PermitRepository extends Repository
{
    /** @var array<string>|null */
    protected ?array $searchableColumns = ['title'];

    /** @var array<string>|null */
    protected ?array $sortableColumns = ['created_at'];

    protected string $defaultSort = 'created_at';

    protected string $defaultSortDirection = self::SORT_DIRECTION_DESC;

    protected string $model = \App\Domains\Permit\Models\Permit::class;

    /** @var array<string>|null */
    protected ?array $with = ['user'];

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

    protected function user(?int $userId): static
    {
        if (! empty($userId)) {
            $this->query->where('user_id', $userId);
        }

        return $this;
    }

    protected function state(?string $value): static
    {
        if (! empty($value) && in_array($value, [
            \App\Domains\Permit\State\Permit\Pending::$name,
            \App\Domains\Permit\State\Permit\Revision::$name,
            \App\Domains\Permit\State\Permit\Approved::$name,
            \App\Domains\Permit\State\Permit\Rejected::$name,
            \App\Domains\Permit\State\Permit\Canceled::$name,
        ])) {
            $this->query->where('state', $value);
        }

        return $this;
    }

    protected function type(?string $value): static
    {
        if (! empty($value) && in_array($value, PermitType::toArray())) {
            $this->query->where('type', $value);
        }

        return $this;
    }
}
