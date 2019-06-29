<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

class CallableHandler implements iHandler
{
    /**
     * @var callable
     */
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
