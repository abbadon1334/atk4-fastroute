<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

use atk4\ui\App;

trait AfterRoutableTrait {
    /** @var callable Store the OnAfter function if defined manually */
    protected $func_after_route;

    /**
     * @param callable $callable
     */
    public function setAfterRoute(callable $callable): void {
        $this->func_after_route = $callable;
    }

    /**
     * @param App   $app
     * @param mixed ...$parameters
     *
     * @internal
     */
    public function OnAfterRoute(App $app, ...$parameters): void {
        if (null !== $this->func_after_route) {
            ($this->func_after_route)($app, ...$parameters);
        }
    }
}
