<?php

declare(strict_types=1);

namespace App\Support\Resource;

use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ResourceTransformer
{
    /**
     * A data object will automatically be transformed to a JSON response when returned in a controller
     *
     * @param class-string<TValue> $dataClass
     */
    public static function transform(string $dataClass, Collection|LengthAwarePaginator|CursorPaginator $items): PaginatedDataCollection|CursorPaginatedDataCollection|DataCollection
    {
        $type = match (get_class($items)) {
            LengthAwarePaginator::class => PaginatedDataCollection::class,
            CursorPaginator::class => CursorPaginatedDataCollection::class,
            default => DataCollection::class,
        };

        return $dataClass::collect($items, $type);
    }
}
