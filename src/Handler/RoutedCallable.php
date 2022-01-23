<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\AfterRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\BeforeRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iBeforeRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;

class RoutedCallable implements iOnRoute, iAfterRoutable, iBeforeRoutable
{
    use AfterRoutableTrait {
        OnAfterRoute as _OnAfterRoute;
    }

    use BeforeRoutableTrait {
        OnBeforeRoute as _OnBeforeRoute;
    }

    /** @var callable */
    protected $func;

    /** @var array */
    protected $extra_arguments;

    /**
     * RoutedCallable constructor.
     *
     * @param mixed ...$extra_arguments
     */
    public function __construct(callable $func, ...$extra_arguments)
    {
        $this->func = $func;
        $this->extra_arguments = $extra_arguments;
    }

    /**
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function onRoute(...$parameters)
    {
        $arguments = array_merge($this->extra_arguments, $parameters);

        return ($this->func)(...$arguments);
    }
}
