<?php

declare(strict_types=1);

namespace App\Support\Repositories;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Repository
{
    public const SORT_DIRECTION_ASC = 'asc';
    public const SORT_DIRECTION_DESC = 'desc';

    protected string $model;

    /** @var Builder<Model> */
    protected Builder $query;

    protected string $defaultSort = 'created_at';

    protected string $defaultSortDirection = self::SORT_DIRECTION_DESC;

    /** @var array<string>|null */
    protected ?array $searchableColumns = [];

    /** @var array<string>|null */
    protected ?array $sortableColumns = [];

    /** @var array<string>|null */
    protected ?array $with = [];

    /** @var array<string>|null */
    protected ?array $select = null;

    /** @var array<string>|null */
    protected ?array $withCount = [];

    protected ?int $limit = null;

    public function __construct(protected ?CriteriaInterface $criteria = null)
    {
        $this->makeQuery();
    }

    private function makeQuery(): void
    {
        $query = (new $this->model)->newQuery();
        $this->query = ! empty($this->with) ? $query->with($this->with) : $query;
        $this->query = ! empty($this->withCount) ? $query->withCount($this->withCount) : $query;

        if ($this->criteria) {
            foreach ($this->criteria->toArray() as $key => $value) {
                $key = Str::camel($key);
                if (method_exists($this, $key)) {
                    $this->$key($value);
                }
            }
        }
    }

    public function search(?string $keyword): static
    {
        if (! $keyword) {
            return $this;
        }

        $likeKeyword = 'pgsql' === config('database.default')
            ? 'ilike'
            : 'like';

        if (! empty($this->searchableColumns)) {
            $keyword = sprintf('%%%s%%', $keyword);

            /** @var array<string> $searchableColumns */
            $searchableColumns = $this->searchableColumns;

            $this->query = $this->query->where(function ($query) use ($likeKeyword, $searchableColumns, $keyword) {
                foreach ($searchableColumns as $column) {
                    if (Str::contains($column, '.')) {
                        $query->orWhereHas(Str::before($column, '.'), function ($query) use ($likeKeyword, $column, $keyword) {
                            $query->where(Str::after($column, '.'), $likeKeyword, $keyword);
                        });
                    } else {
                        $query->orWhere($column, $likeKeyword, $keyword);
                    }
                }
            });
        }

        return $this;
    }

    public function select(?array $select = null): self
    {
        if ($select) {
            $this->select = $select;
        }

        return $this;
    }

    public function limit(?int $limit): void
    {
        if ($limit) {
            $this->limit = $limit;
        }
    }

    /**
     * @param  array<string,string>|null                         $appends
     * @return LengthAwarePaginator<Model>|Collection<int,Model>
     */
    public function paginate(array $appends = []): LengthAwarePaginator|Collection
    {
        $this->orderBy();

        return $this->query
            ->paginate(
                perPage: $this->limit ?? 25,
                page: $appends['page'] ?? null,
                columns: $this->select ?? ['*']
            )
            ->appends($appends);
    }

    /**
     * @return Collection<int,Model>
     */
    public function get(): Collection
    {
        $this->orderBy();

        return $this->query
            ->limit($this->limit ?? null)
            ->get($this->select ?? ['*']);
    }

    protected function orderBy(): static
    {
        $this->query->orderBy($this->defaultSort, $this->defaultSortDirection);

        return $this;
    }

    /**
     * @param  array<string,string>|null $appends
     * @return CursorPaginator<Model>
     */
    public function cursorPaginate(array $appends = []): CursorPaginator
    {
        $this->orderBy();

        return $this->query
            ->cursorPaginate($this->limit ?? 25)
            ->appends($appends);
    }
}
