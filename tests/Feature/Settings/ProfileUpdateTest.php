<?php

declare(strict_types=1);

use App\Domains\User\Models\User;

use function Pest\Laravel\actingAs;

test('profile information can be updated', function () {
    /** @var User */
    $user = User::factory()->create();

    $response = actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertNoContent();

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
});