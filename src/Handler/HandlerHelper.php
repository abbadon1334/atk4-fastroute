<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use atk4\ui\jsExpressionable;

class HandlerHelper
{
    /**
     * @param array $array
     *
     * @return iOnRoute
     * @throws \ReflectionException
     */
    public static function fromArray(array $array): iOnRoute
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
}
