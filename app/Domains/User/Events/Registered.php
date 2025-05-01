<?php

declare(strict_types=1);

namespace App\Domains\User\Events;

use Illuminate\Queue\SerializesModels;

class Registered
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user The authenticated user.
     */
    public function __construct(
        public $user
    ) {}
}
