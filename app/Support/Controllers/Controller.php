<?php

declare(strict_types=1);

namespace App\Support\Controllers;

use App\Support\Resource\ResourceTransformer;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

abstract class Controller extends \Illuminate\Routing\Controller
{
    /**
     * Transform resource
     *
     * @param class-string                                                                                                      $dataClass
     * @param \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Pagination\CursorPaginator $items
     */
    protected function resource(string $dataClass, $items, ...$includes): PaginatedDataCollection|CursorPaginatedDataCollection|DataCollection
    {
        return ResourceTransformer::transform(
            $dataClass,
            $items
        )->include(...$includes);
    }
}
