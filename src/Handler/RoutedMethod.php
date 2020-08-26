<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\AfterRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\BeforeRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iArrayable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iBeforeRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use ReflectionException;
use ReflectionMethod;

class RoutedMethod implements iOnRoute, iArrayable, iAfterRoutable, iBeforeRoutable
{
    use AfterRoutableTrait {
        OnAfterRoute as _OnAfterRoute;
    }

    use BeforeRoutableTrait {
        OnBeforeRoute as _OnBeforeRoute;
    }

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

    /**
     * RoutedMethod constructor.
     *
     * @param string $ClassName
     * @param string $ClassMethod
     */
    public function __construct(string $ClassName, string $ClassMethod)
    {
        $this->ClassName = $ClassName;
        $this->ClassMethod = $ClassMethod;
    }

    /**
     * @param array $array
     *
     * @return iOnRoute
     */
    public static function fromArray(array $array): iOnRoute
    {
        return new static(...$array);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [$this->ClassName, $this->ClassMethod];
    }

    /**
     * @param mixed ...$parameters
     *
     * @throws ReflectionException
     */
    public function onRoute(...$parameters): void
    {
        $class = $this->ClassName;
        $method = $this->ClassMethod;

        $MethodChecker = new ReflectionMethod($class, $method);

        if ($MethodChecker->isStatic()) {
            $this->onRouteResult = $class::{$method}(...$parameters);

            return;
        }

        $class = new $class();
        $this->onRouteResult = $class->{$method}(...$parameters);
    }
}
