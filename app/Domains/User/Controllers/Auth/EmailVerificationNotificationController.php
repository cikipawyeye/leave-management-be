<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers\Auth;

use App\Support\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends ApiController
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendJsonResponse(
                message: 'email-already-verified',
                status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->sendJsonResponse(
            status: JsonResponse::HTTP_NO_CONTENT
        );
    }
}
