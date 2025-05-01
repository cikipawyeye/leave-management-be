<?php

declare(strict_types=1);

namespace App\Domains\User\DataTransferObjects;

use App\Domains\User\Models\User;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class UserData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly ?string $email,
        public readonly string|Carbon|null $email_verified_at,
        public readonly string|Carbon|null $created_at,
        public readonly Lazy|string|null $password,
        public readonly Lazy|string|null $role,
        public readonly Lazy|array|null $permissions,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            email_verified_at: $user->email_verified_at,
            created_at: $user->created_at,
            password: Lazy::create(fn () => null),
            role: Lazy::create(fn () => $user->roles->first()?->name),
            permissions: Lazy::create(fn () => $user->getAllPermissions()->pluck('name')),
        );
    }
}
