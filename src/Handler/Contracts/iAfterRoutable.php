<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use Atk4\Ui\App;

interface iAfterRoutable
{
    /**
     * @return mixed
     */
    public function setAfterRoute(callable $callable);

    /**
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function OnAfterRoute(App $app, ...$parameters);
}
