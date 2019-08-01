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
        $this->route   = $route;
        $this->handler = $handler;
    }

    /**
     * @param array $route
     *
     * @throws \ReflectionException
     *
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
    public function setHandler(iOnRoute $routeHandler): void
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

    /**
     * @param array         $handler_array
     * @param callable|null $callbackOnBefore
     * @param callable|null $callbackOnAfter
     *
     * @throws Exception
     *
     * @return iOnRoute
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
            throw new Exception([
                'Error Transforming Route to Array',
                'array' => $handler_array,
            ]);
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
