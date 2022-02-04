<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use Atk4\Ui\App;

trait AfterRoutableTrait
{
    /** @var callable Store the OnAfter function if defined manually */
    protected $func_after_route;

    public function setAfterRoute(callable $callable): void
    {
        $this->func_after_route = $callable;
    }

    /**
     * @param mixed ...$parameters
     *
     * @internal
     */
    public function OnAfterRoute(App $app, ...$parameters): void
    {
        if ($this->func_after_route !== null) {
            ($this->func_after_route)($app, ...$parameters);
        }
    }
}
