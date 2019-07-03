<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

interface iBeforeRoutable
{
    /**
     * @param callable $callable
     *
     * @return mixed
     */
    public function setBeforeRoute(callable $callable);

    /**
     * @param App   $app
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function OnBeforeRoute(App $app, ...$parameters);
}
