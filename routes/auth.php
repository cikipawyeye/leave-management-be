<?php

declare(strict_types=1);

use App\Domains\User\Controllers\Auth\AuthenticatedSessionController;
use App\Domains\User\Controllers\Auth\ConfirmablePasswordController;
use App\Domains\User\Controllers\Auth\EmailVerificationNotificationController;
use App\Domains\User\Controllers\Auth\EmailVerificationPromptController;
use App\Domains\User\Controllers\Auth\NewPasswordController;
use App\Domains\User\Controllers\Auth\PasswordResetLinkController;
use App\Domains\User\Controllers\Auth\RegisteredUserController;
use App\Domains\User\Controllers\Auth\VerifyEmailController;
use App\Domains\User\Enums\RoleEnum;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::middleware(sprintf('role:%s|%s', RoleEnum::Admin->value, RoleEnum::Verificator->value))->group(function () {
        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->withoutMiddleware('verified')
            ->name('verification.send');
    });

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
