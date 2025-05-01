<?php

declare(strict_types=1);

namespace App\Domains\User\Controllers\Auth;

use App\Domains\User\Enums\RoleEnum;
use App\Support\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
    public function __invoke(Request $request): Response|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($request->user()->hasRole(RoleEnum::User->value)) {
            return Inertia::render('auth/waiting-verification');
        }

        return Inertia::render('auth/verify-email', ['status' => $request->session()->get('status')]);
    }
}
