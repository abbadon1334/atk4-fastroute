<?php


namespace Handler;


interface iHandlerBeforeRoute
{
    public function OnBeforeRoute(\atk4\ui\App $app);
}