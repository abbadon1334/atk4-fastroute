<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

interface iOnRoute {
    /**
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function onRoute(...$parameters);
}
