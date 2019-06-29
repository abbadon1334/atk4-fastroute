<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\AfterRouteableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\BeforeRouteableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRouteable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iArrayable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iBeforeRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iNeedAppRun;
use atk4\ui\App;

class RoutedUI implements iOnRoute, iArrayable, iAfterRouteable, iBeforeRoutable, iNeedAppRun
{
    use AfterRouteableTrait {
        OnAfterRoute as _OnAfterRoute;
    }

    use BeforeRouteableTrait {
        OnBeforeRoute as _OnBeforeRoute;
    }

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

    public static function fromArray(array $array): iOnRoute
    {
        return new static(...$array);
    }

    public function toArray(): array
    {
        return [$this->ClassName, $this->default];
    }

    public function onRoute(...$parameters): void
    {
        $class = $this->ClassName;

        $this->onRouteResult = new $class($this->default);
    }

    public function OnAfterRoute(App $app, ...$parameters): void
    {
        $app->add($this->onRouteResult);

        $this->_OnAfterRoute($app,...$parameters);
    }

    public function OnBeforeRoute(App $app, ...$parameters)
    {
        if(is_null($this->func_before_route)) {
            if(!isset($app->html))
            {
                $app->initLayout('Generic');
            }
        }

        $this->_OnBeforeRoute($app,...$parameters);
    }
}
