<?php

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

trait BeforeRoutableTrait
{
    /** @var callable Store the OnBefore function if defined manually */
    protected $func_before_route;

    public function setBeforeRoute(callable $callable)
    {
        $this->func_before_route = $callable;
    }

    public function OnBeforeRoute(App $app, ...$parameters)
    {
        if(!is_null($this->func_before_route)) {
            ($this->func_before_route)($app,...$parameters);
        }
    }
}