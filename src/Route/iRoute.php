<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;

interface iRoute
{
    public function getMethods(): array;

    public function getRoute(): string;

    public function getHandler(): iOnRoute;

    public function toArray(): array;

    public static function fromArray(array $array): iRoute;
}
