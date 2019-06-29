<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

class UIHandler implements iHandler,iHandlerArrayable
{
    /**
     * @var string
     */
    protected $ClassName;

    /**
     * @var array
     */
    protected $default;

    public function __construct(string $ClassName, array $default = [])
    {
        $this->ClassName = $ClassName;
        $this->default   = $default;
    }

    public static function fromArray(array $array): iHandler
    {
        return new static(...$array);
    }

    public function onRoute(...$parameters)
    {
        $class = $this->ClassName;

        return new $class($this->default, ...$parameters);
    }

    public function toArray(): array
    {
        return [$this->ClassName, $this->default];
    }
}
