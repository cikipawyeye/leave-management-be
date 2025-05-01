<?php

declare(strict_types=1);

namespace App\Domains\Permit\Repositories;

use App\Support\Repositories\Criteria;

class PermitCriteria extends Criteria
{
    public function __construct(
        public readonly ?int $page,
        public readonly ?string $search,
        public readonly ?int $user,
        public readonly ?string $state,
        public readonly ?string $type,
    ) {}
}
