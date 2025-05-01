<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\PermitReview;

class Approved extends PermitReviewState
{
    public static string $name = 'approved';

    public static function stateLabel(): string
    {
        return __('app.approved');
    }
}
