<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Support\Repositories\Criteria;

class UserCriteria extends Criteria
{
    public function __construct(
        public readonly ?int $page,
        public readonly ?string $search,
        public readonly ?string $sort,
        public readonly ?string $role,
        public readonly ?string $verified,
    ) {}
}
