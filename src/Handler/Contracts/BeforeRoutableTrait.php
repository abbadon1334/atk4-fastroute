<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use Atk4\Ui\App;

trait BeforeRoutableTrait
{
    /** @var callable Store the OnBefore function if defined manually */
    protected $func_before_route;

    public function setBeforeRoute(callable $callable): void
    {
        $this->func_before_route = $callable;
    }

    /**
     * @param mixed ...$parameters
     *
     * @internal
     */
    public function OnBeforeRoute(App $app, ...$parameters): void
    {
        if ($this->func_before_route !== null) {
            ($this->func_before_route)($app, ...$parameters);
        }
    }
}
