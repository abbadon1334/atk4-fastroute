<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

interface iAfterRoutable
{
    public function setAfterRoute(callable $callable);
    public function OnAfterRoute(App $app, ...$parameters);
}
