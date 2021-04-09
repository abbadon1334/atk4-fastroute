<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Handler\RoutedCallable;
use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use atk4\core\Exception;
use atk4\ui\jsExpressionable;
use ReflectionException;

class Route implements iRoute
{
    protected array $methods;

    protected string $route;

    protected ?iOnRoute $handler;

    public function __construct(string $route, ?array $methods = null, ?iOnRoute $handler = null)
    {
        $this->methods = $methods ?? [];
        $this->route   = $route;
        $this->handler = $handler;
    }

    /**
     * @param array $route
     *
     * @throws Exception
     * @return iRoute
     */
    public static function fromArray(array $route): iRoute
    {
        return new static(
            $route[0],
            $route[1],
            self::getHandlerFromArray($route[2], $route[3] ?? null, $route[4] ?? null)
        );
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getHandler(): iOnRoute
    {
        return $this->handler;
    }

    public function addMethod(string ...$method): iRoute
    {
        $this->methods = is_array($method) ? $method : [$method];

        return $this;
    }

    public function setHandler(iOnRoute $routeHandler): void
    {
        $this->handler = $routeHandler;
    }

    public function toArray(): array
    {
        return [
            $this->getMethods(),
            $this->getRoute(),
            $this->getHandler() /*->toArray()*/,
        ];
    }

    /**
     * @throws Exception
     */
    private static function getHandlerFromArray(array $handler_array, ?callable $callbackOnBefore, ?callable $callbackOnAfter): iOnRoute
    {
        $handler = null;

        $first_element  = $handler_array[0];
        $second_element = $handler_array[1] ?? null;

        switch (true) {
            case is_callable($first_element):
                $handler = new RoutedCallable($first_element);
                break;

            case is_string($first_element) && is_string($second_element):
                $handler = RoutedMethod::fromArray($handler_array);
                break;

            case is_a($first_element, RoutedServeStatic::class, true):
                $handler = RoutedServeStatic::fromArray($second_element);
                break;

            case is_a($first_element, jsExpressionable::class, true):
                $handler = RoutedUI::fromArray($handler_array);
                break;
        }

        if (null === $handler) {
            throw (new Exception('Error Transforming Route to Array'))->addMoreInfo('array', $handler_array);
        }

        if (null !== $callbackOnBefore) {
            $handler->setBeforeRoute($callbackOnBefore);
        }

        if (null !== $callbackOnAfter) {
            $handler->setAfterRoute($callbackOnAfter);
        }

        return $handler;
    }
}
