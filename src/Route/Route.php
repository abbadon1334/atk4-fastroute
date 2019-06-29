<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\Handler;
use Abbadon1334\ATKFastRoute\Handler\iHandler;
use Abbadon1334\ATKFastRoute\Handler\MethodHandler;
use Abbadon1334\ATKFastRoute\Handler\UIHandler;

class Route implements iRoute
{
    protected $methods;
    protected $route;
    protected $handler;

    public function __construct(string $route, ?array $methods = null, ?iHandler $handler = null)
    {
        $this->methods = $methods ?? [];
        $this->route   = $route;
        $this->handler = $handler;
    }

    public static function fromArray(array $route): iRoute
    {
        return new Route($route[1], $route[0], Handler::fromArray($route[2]));
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getHandler(): iHandler
    {
        return $this->handler;
    }

    public function addMethod(string $method): void
    {
        $this->methods[] = $method;
    }

    public function handleWithUI(string $class, array $defaults = []): void
    {
        $this->handler = new UIHandler($class, $defaults);
    }

    public function handleWithMethod(string $ControllerClass, string $ControllerMethod): void
    {
        $this->handler = new MethodHandler($ControllerClass, $ControllerMethod);
    }

    public function toArray(): array
    {
        return [
            $this->methods,
            $this->route,
            $this->handler /*->toArray()*/,
        ];
    }
}
