<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

interface iArrayable {
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param array $array
     *
     * @return iOnRoute
     */
    public static function fromArray(array $array): iOnRoute;
}
