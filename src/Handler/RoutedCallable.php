<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRouteable;
use atk4\ui\App;

class RoutedCallable implements iOnRoute, iAfterRouteable
{
    /** @var callable */
    protected $func;

    /** @var callable Store the OnAfter function if defined manually */
    protected $func_after_route;

    public function __construct(callable $func)
    {
        $this->func = $func;
    }

    public function onRoute(...$parameters)
    {
        return ($this->func)(...$parameters);
    }

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
