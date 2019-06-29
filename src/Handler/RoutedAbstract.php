<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;

abstract class RoutedAbstract
{
    public static function fromArray(array $array): iOnRoute
    {
        $firstArg  = $array[0];
        $secondArg = $array[1] ?? null;

        if (is_string($firstArg) && is_string($secondArg)) {
            return RoutedMethod::fromArray($array);
        }

        return RoutedUI::fromArray($array);
    }
}
