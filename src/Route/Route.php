<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Handler\RoutedCallable;
use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use atk4\ui\jsExpressionable;

class Route implements iRoute
{
    /**
     * @var array|null
     */
    protected $methods;
    /**
     * @var string
     */
    protected $route;
    /**
     * @var iOnRoute|null
     */
    protected $handler;

    /**
     * Route constructor.
     *
     * @param string        $route
     * @param array|null    $methods
     * @param iOnRoute|null $handler
     */
    public function __construct(string $route, ?array $methods = null, ?iOnRoute $handler = null)
    {
        $this->methods = $methods ?? [];
        $this->route = $route;
        $this->handler = $handler;
    }

    /**
     * @param array $route
     *
     * @return iRoute
     * @throws \ReflectionException
     */
    public static function fromArray(array $route): iRoute
    {
        return new static($route[0], $route[1], self::getHandlerFromArray($route[2]));
    }

    /**
     * @param array $array
     *
     * @throws \ReflectionException
     *
     * @return iOnRoute
     */
    private static function getHandlerFromArray(array $array): iOnRoute
    {
        $firstArg = $array[0];

        if (function_exists($firstArg)) {
            return new RoutedCallable(...$array);
        }

        $checkClass = new \ReflectionClass($firstArg);
        if ($checkClass->isSubclassOf(jsExpressionable::class)) {
            return RoutedUI::fromArray($array);
        }

        return RoutedMethod::fromArray($array);
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return iOnRoute
     */
    public function getHandler(): iOnRoute
    {
        return $this->handler;
    }

    /**
     * @param string $method
     *
     * @return iRoute
     */
    public function addMethod(string $method): iRoute
    {
        $this->methods[] = $method;

        return $this;
    }

    /**
     * @param iOnRoute $routeHandler
     */
    public function setHandler(iOnRoute $routeHandler) : void
    {
        $this->handler = $routeHandler;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->getMethods(),
            $this->getRoute(),
            $this->getHandler() /*->toArray()*/,
        ];
    }
}
