<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers\Auth;

use App\Domains\User\DataTransferObjects\UserData;
use App\Domains\User\Requests\Auth\LoginRequest;
use App\Support\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AuthenticatedSessionController extends ApiController
{
    public function profile(Request $request): JsonResponse
    {
        return $this->sendJsonResponse(
            data: UserData::fromModel($request->user())->include('role', 'permissions'),
            message: __('app.succeeded')
        );
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        return $this->sendJsonResponse([
            'token' => $request->user()->createToken('auth_token')->plainTextToken,
        ], message: __('app.succeeded'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        /** @disregard P1013 */
        $request->user()?->currentAccessToken()?->delete();

        return $this->sendJsonResponse(status: HttpFoundationResponse::HTTP_NO_CONTENT);
    }
}
