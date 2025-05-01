<?php

declare(strict_types=1);

namespace App\Support\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseException extends AbstractException
{
    public function render(): JsonResponse
    {
        $code = (array_key_exists($this->getCode(), Response::$statusTexts)) ? $this->getCode() : 500;

        return response()->json(['message' => $this->getMessage()], $code);
    }
}
