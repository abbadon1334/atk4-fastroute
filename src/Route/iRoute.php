<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;

interface iRoute
{
    /**
     * @return iRoute
     */
    public static function fromArray(array $array): self;

    public function toArray(): array;

    public function getMethods(): array;

    public function getRoute(): string;

    public function getHandler(): iOnRoute;

    /**
     * @return iRoute
     */
    public function addMethod(string ...$method): self;

    public function setHandler(iOnRoute $routeHandler): void;
}
