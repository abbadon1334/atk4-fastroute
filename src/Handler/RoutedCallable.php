<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;

class RoutedCallable implements iOnRoute
{
    /** @var callable */
    protected $func;

    /** @var array */
    protected $extra_arguments;

    /**
     * RoutedCallable constructor.
     *
     * @param callable $func
     * @param mixed    ...$extra_arguments
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
