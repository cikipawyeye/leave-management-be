<?php

declare(strict_types=1);

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;

test('password can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->putJson(route('password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertNoContent();

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    /** @var User */
    $user = User::factory()->create();

    $response = actingAs($user)
        ->putJson(route('password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertUnprocessable();
});
