<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use Atk4\Ui\App;

interface iBeforeRoutable
{
    /**
     * @return mixed
     */
    public function setBeforeRoute(callable $callable);

    /**
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function OnBeforeRoute(App $app, ...$parameters);
}
