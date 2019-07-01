<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

interface iBeforeRoutable
{
    public function setBeforeRoute(callable $callable);

    public function OnBeforeRoute(App $app, ...$parameters);
}
