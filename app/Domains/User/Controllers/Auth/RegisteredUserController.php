<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers\Auth;

use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Events\Registered;
use App\Domains\User\Models\User;
use App\Support\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class RegisteredUserController extends ApiController
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();
            /** @var User */
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole(RoleEnum::User->value);

            DB::commit();

            event(new Registered($user));

            return $this->sendJsonResponse(status: HttpFoundationResponse::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
