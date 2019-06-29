<?php

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

trait AfterRouteableTrait
{
    /** @var callable Store the OnAfter function if defined manually */
    protected $func_after_route;

    public function setAfterRoute(callable $callable)
    {
        $this->func_after_route = $callable;
    }

    public function OnAfterRoute(App $app, ...$parameters)
    {
        if(!is_null($this->func_after_route))
        {
            ($this->func_after_route)($app,...$parameters);
        }
    }
}