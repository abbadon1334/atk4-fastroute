<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

interface iHandlerBeforeRoute
{
    public function OnBeforeRoute(\atk4\ui\App $app);
}
