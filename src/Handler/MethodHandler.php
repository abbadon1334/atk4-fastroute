<?php declare(strict_types=1);


namespace Abbadon1334\ATKFastRoute\Handler;

class MethodHandler implements iHandler,iHandlerArrayable
{
    /**
     * Class Name to be called.
     *
     * @var string
     */
    protected $ClassName;

    /**
     * Class Method to be called.
     *
     * @var string
     */
    protected $ClassMethod;

    /**
     * Store the result of the onRoute call.
     *
     * @var mixed
     */
    protected $onRouteResult;


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

        $this->onRouteResult = $class::{$this->ClassMethod}(...$parameters);
    }

    public function toArray(): array
    {
        return [$this->ClassName, $this->ClassMethod];
    }
}
