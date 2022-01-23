<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\AfterRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\BeforeRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iArrayable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iBeforeRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iNeedAppRun;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Atk4\Ui\App;
use Atk4\Ui\Layout;

class RoutedUI implements iOnRoute, iArrayable, iNeedAppRun, iAfterRoutable, iBeforeRoutable
{
    use AfterRoutableTrait {
        OnAfterRoute as _OnAfterRoute;
    }

    use BeforeRoutableTrait {
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

    /**
     * @var
     */
    protected $onRouteResult;

    /**
     * RoutedUI constructor.
     */
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

    /**
     * @param mixed ...$parameters
     */
    public function onRoute(...$parameters): void
    {
        $class = $this->ClassName;

        $this->onRouteResult = new $class($this->default);
    }

    /**
     * @param mixed ...$parameters
     *
     * @internal
     */
    public function OnAfterRoute(App $app, ...$parameters): void
    {
        if (null !== $this->onRouteResult) {
            $app->add($this->onRouteResult);

            if (method_exists($this->onRouteResult, 'onRoute')) {
                $this->onRouteResult->onRoute(...$parameters);
            }
        }

        $this->_OnAfterRoute($app, ...$parameters);
    }

    /**
     * @param mixed ...$parameters
     *
     * @internal
     */
    public function OnBeforeRoute(App $app, ...$parameters): void
    {
        if (!isset($app->html) && null === $this->func_before_route) {
            $app->initLayout([Layout::class]);
        }

        $this->_OnBeforeRoute($app, ...$parameters);
    }
}
