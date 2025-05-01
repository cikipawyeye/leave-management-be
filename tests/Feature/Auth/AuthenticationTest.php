<?php

declare(strict_types=1);

use App\Domains\User\Models\User;

use function Pest\Laravel\postJson;

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertJsonStructure([
        'data' => ['token']
    ]);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertUnprocessable();
});