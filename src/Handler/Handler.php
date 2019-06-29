<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

abstract class Handler implements iHandler
{
    public static function fromArray(array $array): iHandler
    {
        $firstArg  = $array[0];
        $secondArg = $array[1] ?? null;

        if (is_string($firstArg) && is_string($secondArg)) {
            return MethodHandler::fromArray($array);
        }

        return UIHandler::fromArray($array);
    }

    abstract public function onRoute(...$parameters);

    abstract public function toArray(): array;
}
