<?php

declare(strict_types=1);

namespace App\Domains\Permit\State\PermitReview;

use Spatie\ModelStates\State;

abstract class PermitReviewState extends State
{
    abstract public static function stateLabel(): string;
}
