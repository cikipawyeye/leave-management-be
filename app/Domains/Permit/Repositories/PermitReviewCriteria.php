<?php

declare(strict_types=1);

namespace App\Domains\Permit\Repositories;

use App\Support\Repositories\Criteria;

class PermitReviewCriteria extends Criteria
{
    public function __construct(
        public readonly ?int $page,
        public readonly ?string $search,
        public readonly ?string $sort,
        public readonly ?int $permit,
        public readonly ?string $state,
    ) {}
}
