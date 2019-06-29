<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Handler\iHandlerAfterRoute;
use Handler\iHandlerBeforeRoute;

class UIHandler implements iHandler,iHandlerArrayable,iHandlerAfterRoute
{
    /**
     * @var string
     */
    protected $ClassName;

    /**
     * @var array
     */
    protected $default;

    protected $onRouteResult;

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

        $this->onRouteResult = new $class($this->default, ...$parameters);
    }

    public function toArray(): array
    {
        return [$this->ClassName, $this->default];
    }

    public function OnAfterRoute(\atk4\ui\App $app)
    {
        $app->add($this->onRouteResult);
    }
}
