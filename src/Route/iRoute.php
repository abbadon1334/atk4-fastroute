<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\iHandler;

interface iRoute
{
    public static function fromArray(array $array): iRoute;

    public function getMethods(): array;

    public function getRoute(): string;

    public function getHandler(): iHandler;

    public function toArray(): array;
}
