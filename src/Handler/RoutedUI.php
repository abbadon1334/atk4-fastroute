<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRouteable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iArrayable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iBeforeRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iNeedAppRun;
use atk4\ui\App;

class RoutedUI implements iOnRoute, iArrayable, iAfterRouteable, iBeforeRoutable, iNeedAppRun
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

    /** @var callable Store the OnBefore function if defined manually */
    protected $func_before_route;

    /** @var callable Store the OnAfter function if defined manually */
    protected $func_after_route;

    public function __construct(string $ClassName, array $default = [])
    {
        $this->ClassName = $ClassName;
        $this->default   = $default;
    }

    public static function fromArray(array $array): iOnRoute
    {
        return new static(...$array);
    }

    public function onRoute(...$parameters): void
    {
        $class = $this->ClassName;

        $this->onRouteResult = new $class($this->default);
    }

    public function toArray(): array
    {
        return [$this->ClassName, $this->default];
    }

    public function OnAfterRoute(App $app, ...$parameters): void
    {
        if(is_null($this->func_after_route)) {
            $app->add($this->onRouteResult);
            return;
        }

        ($this->func_after_route)($app,...$parameters);
    }

    public function setAfterRoute(callable $callable)
    {
        $this->func_after_route = $callable;
    }

    public function setBeforeRoute(callable $callable)
    {
        $this->func_before_route = $callable;
    }

    public function OnBeforeRoute(App $app, ...$parameters)
    {
        if(is_null($this->func_before_route)) {
            if(!isset($app->html))
            {
                $app->initLayout('Generic');
            }
            return;
        }

        ($this->func_before_route)($app,...$parameters);
    }
}
