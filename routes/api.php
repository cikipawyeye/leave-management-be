<?php

declare(strict_types=1);

use App\Domains\Permit\Controllers\PermitController;
use App\Domains\Permit\Controllers\PermitReviewController;
use App\Domains\User\Controllers\Auth\AuthenticatedSessionController;
use App\Domains\User\Controllers\Auth\RegisteredUserController;
use App\Domains\User\Controllers\OrdinaryUserController;
use App\Domains\User\Controllers\Settings\PasswordController;
use App\Domains\User\Controllers\Settings\ProfileController;
use App\Domains\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('me', [AuthenticatedSessionController::class, 'profile'])->withoutMiddleware('verified')->name('me');

    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::apiResource('users', UserController::class)->only(['index', 'store', 'show', 'update']);
    Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    Route::apiResource('ordinary-users', OrdinaryUserController::class)->only(['index', 'show']);
    Route::post('ordinary-users/{ordinary_user}/verify', [OrdinaryUserController::class, 'verify'])->name('ordinary-users.verify');

    Route::apiResource('permits', PermitController::class);
    Route::post('permits/{permit}/cancel', [PermitController::class, 'cancel'])->name('permits.cancel');

    Route::apiResource('permit-reviews', PermitReviewController::class)->only(['index', 'store', 'show']);
});

Route::middleware('guest')->group(function () {
    Route::post('register', [RegisteredUserController::class, 'store']);
});
