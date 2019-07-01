<?php

return [
    [
        '/test',
        ['GET', 'POST'],
        ['StandardClass', 'handleRequest'],
    ],
    [
        '/testStatic',
        ['GET', 'POST'],
        ['StandardClass', 'staticHandleRequest'],
    ],
    [
        '/test2',
        ['GET', 'POST'],
        ['ATKView', ['text' => 'it works']],
    ],
    [
        '/callable',
        ['GET', 'POST'],
        ['handleWithFunction'],
    ],
    [
        '/test-parameters/{id:\d+}/{title}',
        ['GET'],
        ['StandardClass', 'HandleRequest'],
    ],
    [
        '/test-parameters-static/{id:\d+}/{title}',
        ['GET'],
        ['StandardClass', 'staticHandleRequest'],
    ],
];
