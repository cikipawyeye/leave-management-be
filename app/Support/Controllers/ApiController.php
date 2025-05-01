<?php

declare(strict_types=1);

namespace App\Support\Controllers;

use App\Support\Resource\ResourceTransformer;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends \Illuminate\Routing\Controller
{
    /**
     * Send json response
     *
     * @param int $options
     */
    protected function sendJsonResponse(
        mixed $data = null,
        int $status = Response::HTTP_OK,
        ?string $message = null,
        array $headers = [],
        $options = 0
    ): \Illuminate\Http\JsonResponse {
        if (
            $data instanceof AbstractPaginator ||
            $data instanceof AbstractCursorPaginator ||
            $data instanceof PaginatedDataCollection ||
            $data instanceof CursorPaginatedDataCollection
        ) {
            return response()->json($data, $status, $headers, $options);
        }

        $response = [];
        $response['data'] = $data;

        if (null !== $message) {
            $response['message'] = $message;
        }

        return response()->json($response, $status, $headers, $options);
    }

    /**
     * Send data resource
     *
     * @param class-string                                                                                                      $dataClass
     * @param \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Pagination\CursorPaginator $items
     */
    protected function resource(string $dataClass, $items, ...$includes): \Illuminate\Http\JsonResponse
    {
        return $this->sendJsonResponse(
            ResourceTransformer::transform(
                $dataClass,
                $items
            )->include(...$includes)
        );
    }
}
