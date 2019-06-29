<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;

class RoutedCallable implements iOnRoute
{
    /** @var callable */
    protected $func;

    public function __construct(callable $func)
    {
        $this->func = $func;
    }

    public function onRoute(...$parameters)
    {
        return ($this->func)(...$parameters);
    }
}
