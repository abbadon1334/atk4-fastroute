<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Route;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;

interface iRoute
{
    /**
     * @param array $array
     *
     * @return iRoute
     */
    public static function fromArray(array $array): self;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function getMethods(): array;

    /**
     * @return string
     */
    public function getRoute(): string;

    /**
     * @return iOnRoute
     */
    public function getHandler(): iOnRoute;

    /**
     * @param string $method
     *
     * @return iRoute
     */
    public function addMethod(string $method): self;

    /**
     * @param iOnRoute $routeHandler
     */
    public function setHandler(iOnRoute $routeHandler): void;
}
