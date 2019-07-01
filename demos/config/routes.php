<?php

return [
    [
        ['GET', 'POST'],
        '/test',
        ['StandardClass', 'handleRequest'],
    ],
    [
        ['GET', 'POST'],
        '/testStatic',
        ['StandardClass', 'staticHandleRequest'],
    ],
    [
        ['GET', 'POST'],
        '/test2',
        ['ATKView', ['text' => 'it works']],
    ],
    [
        ['GET', 'POST'],
        '/callable',
        ['handleWithFunction'],
    ],
    [
        ['GET'],
        '/test-parameters/{id:\d+}/{title}',
        ['StandardClass', 'HandleRequest'],
    ],
    [
        ['GET'],
        '/test-parameters-static/{id:\d+}/{title}',
        ['StandardClass', 'staticHandleRequest'],
    ],
];
