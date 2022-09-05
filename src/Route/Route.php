<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Handler\RoutedCallable;
use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use Atk4\Core\Exception;
use Atk4\Core\Factory;
use Atk4\Ui\JsExpressionable;

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
     */
    public function __construct(string $route, array $methods = null, iOnRoute $handler = null)
    {
        $this->methods = $methods ?? [];
        $this->route = $route;
        $this->handler = $handler;
    }

    public static function fromArray(array $route): iRoute
    {
        /** @var iRoute $iroute */
        $iroute = Factory::factory(
            [
                static::class,
            ],
            [
                $route[0],
                $route[1],
                self::getHandlerFromArray(
                    $route[2],
                    $route[3] ?? null,
                    $route[4] ?? null,
                ),
            ]
        );

        return $iroute;
    }

    private static function getHandlerFromArray(array $handler_array, ?callable $callbackOnBefore, ?callable $callbackOnAfter): iOnRoute
    {
        $handler = null;

        $first_element = $handler_array[0];
        $second_element = $handler_array[1] ?? null;

        switch (true) {
            case is_callable($first_element):
                $handler = Factory::factory([RoutedCallable::class], [$first_element]);

                break;
            case is_string($first_element) && is_string($second_element):
                $handler = Factory::factory([RoutedMethod::class], $handler_array);

                break;
            case is_a($first_element, RoutedServeStatic::class, true):
                $handler = Factory::factory([RoutedServeStatic::class], $second_element);

                break;
            case is_a($first_element, jsExpressionable::class, true):
                $handler = Factory::factory([RoutedUI::class], $handler_array);

                break;
        }

        if ($handler === null) {
            throw (new Exception('Error Transforming Route to Array'))
                ->addMoreInfo('array', $handler_array);
        }

        if ($callbackOnBefore !== null) {
            $handler->setBeforeRoute($callbackOnBefore);
        }

        if ($callbackOnAfter !== null) {
            $handler->setAfterRoute($callbackOnAfter);
        }

        return $handler;
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
        $this->methods = $method;

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
            $this->getHandler() /* ->toArray() */,
        ];
    }
}
