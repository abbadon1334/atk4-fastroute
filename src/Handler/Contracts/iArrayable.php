<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler\Contracts;

interface iArrayable
{
    public function toArray(): array;
    public static function fromArray(array $array): iOnRoute;
}
