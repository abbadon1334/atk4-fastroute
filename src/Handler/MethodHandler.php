<?php declare(strict_types=1);


namespace Abbadon1334\ATKFastRoute\Handler;

class MethodHandler implements iHandler,iHandlerArrayable
{
    /**
     * @var string
     */
    protected $ClassName;

    /**
     * @var string
     */
    protected $ClassMethod;

    public function __construct(string $ClassName, string $ClassMethod)
    {
        $this->ClassName   = $ClassName;
        $this->ClassMethod = $ClassMethod;
    }

    public static function fromArray(array $array): iHandler
    {
        return new self(...$array);
    }

    public function onRoute(...$parameters)
    {
        $class = new $this->ClassName();

        return $class::{$this->ClassMethod}(...$parameters);
    }

    public function toArray(): array
    {
        return [$this->ClassName, $this->ClassMethod];
    }
}
