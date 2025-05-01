<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Enums\RoleEnum;
use App\Support\Repositories\Repository;

class UserRepository extends Repository
{
    /** @var array<string>|null */
    protected ?array $searchableColumns = ['name', 'email'];

    /** @var array<string>|null */
    protected ?array $sortableColumns = ['name'];

    protected string $defaultSort = 'name';

    protected string $defaultSortDirection = self::SORT_DIRECTION_ASC;

    protected string $model = \App\Domains\User\Models\User::class;

    /** @var array<string>|null */
    protected ?array $with = ['roles'];

    public function role(?string $role): self
    {
        if (in_array($role, RoleEnum::toArray())) {
            $this->query->whereHas('roles', function ($builder) use ($role) {
                $builder->where('name', $role);
            });
        }

        return $this;
    }

    protected function sort($value): static
    {
        if ('latest' == $value) {
            $this->defaultSort = 'created_at';
            $this->defaultSortDirection = self::SORT_DIRECTION_DESC;
        } elseif ('oldest' == $value) {
            $this->defaultSort = 'created_at';
            $this->defaultSortDirection = self::SORT_DIRECTION_ASC;
        } elseif ('name' == $value) {
            $this->defaultSort = 'name';
            $this->defaultSortDirection = self::SORT_DIRECTION_ASC;
        }

        return $this;
    }

    protected function verified(?string $value): static
    {
        if (null === $value) {
            return $this;
        }

        if ('verified' == $value) {
            $this->query->whereNotNull('email_verified_at');
        } elseif ('unverified' == $value) {
            $this->query->whereNull('email_verified_at');
        }

        return $this;
    }
}
