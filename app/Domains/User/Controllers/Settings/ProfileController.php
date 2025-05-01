<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers\Settings;

use App\Domains\User\Requests\Settings\ProfileUpdateRequest;
use App\Support\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ProfileController extends ApiController
{
    /**
     * Update the user's profile settings.
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $request->user()->fill($request->validated());

        // If the user has changed their email address, we need to set the email_verified_at column to null
        // if ($request->user()->isDirty('email')) {
        //     $request->user()->email_verified_at = null;
        //     $request->user()->sendEmailVerificationNotification();
        // }

        $request->user()->save();

        return $this->sendJsonResponse(status: HttpFoundationResponse::HTTP_NO_CONTENT);
    }
}
