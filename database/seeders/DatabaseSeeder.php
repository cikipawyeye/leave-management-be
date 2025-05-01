<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\User\Enums\RoleEnum;
use App\Domains\User\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        RoleEnum::getEnumCollection()->each(function (RoleEnum $role) {
            /** @var User */
            $user = User::factory()->create([
                'name' => $role->translated(),
                'email' => sprintf('%s@example.com', $role->value),
            ]);

            $user->assignRole($role->value);
        });
    }
}
