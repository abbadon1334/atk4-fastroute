<?php


namespace Handler;


interface iHandlerAfterRoute
{
    public function OnAfterRoute(\atk4\ui\App $app);
}