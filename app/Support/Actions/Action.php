<?php

declare(strict_types=1);

namespace App\Support\Actions;

use Illuminate\Foundation\Bus\Dispatchable;

abstract class Action
{
    use Dispatchable;

    /**
     * @return mixed|void
     */
    abstract public function handle();
}
