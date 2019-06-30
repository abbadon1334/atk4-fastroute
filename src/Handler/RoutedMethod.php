<?php declare(strict_types=1);


namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iArrayable;

class RoutedMethod implements iOnRoute, iArrayable
{
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


    public function __construct(string $ClassName, string $ClassMethod)
    {
        $this->ClassName   = $ClassName;
        $this->ClassMethod = $ClassMethod;
    }

    public function onRoute(...$parameters): void
    {
        $class = $this->ClassName;
        $method = $this->ClassMethod;

        $MethodChecker = new \ReflectionMethod($class,$method);

        if($MethodChecker->isStatic())
        {
            $this->onRouteResult = $class::{$method}(...$parameters);
            return;
        }

        $class = new $class();
        $this->onRouteResult = $class->{$method}(...$parameters);
    }

    public static function fromArray(array $array): iOnRoute
    {
        return new self(...$array);
    }

    public function toArray(): array
    {
        return [$this->ClassName, $this->ClassMethod];
    }
}
