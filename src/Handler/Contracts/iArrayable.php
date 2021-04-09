<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

interface iArrayable
{
    public static function fromArray(array $array): iOnRoute;

    public function toArray(): array;
}
