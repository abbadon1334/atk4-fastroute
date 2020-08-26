<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

interface iArrayable
{
    /**
     * @param array $array
     *
     * @return iOnRoute
     */
    public static function fromArray(array $array): iOnRoute;

    /**
     * @return array
     */
    public function toArray(): array;
}
