<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

trait BeforeRoutableTrait
{
    /** @var callable Store the OnBefore function if defined manually */
    protected $func_before_route;

    public function setBeforeRoute(callable $callable): void
    {
        $this->func_before_route = $callable;
    }

    public function OnBeforeRoute(App $app, ...$parameters): void
    {
        if (null !== $this->func_before_route) {
            ($this->func_before_route)($app, ...$parameters);
        }
    }
}
