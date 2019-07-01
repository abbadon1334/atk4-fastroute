<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

trait AfterRoutableTrait
{
    /** @var callable Store the OnAfter function if defined manually */
    protected $func_after_route;

    public function setAfterRoute(callable $callable): void
    {
        $this->func_after_route = $callable;
    }

    public function OnAfterRoute(App $app, ...$parameters): void
    {
        if (! is_null($this->func_after_route)) {
            ($this->func_after_route)($app, ...$parameters);
        }
    }
}
