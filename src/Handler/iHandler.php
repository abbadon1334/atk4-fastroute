<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

interface iHandler
{
    public function onRoute(...$parameters);
}
