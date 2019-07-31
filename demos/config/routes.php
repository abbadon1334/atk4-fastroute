<?php

use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;

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
        function($app,...$parameters)
        {
            echo 'BEFORE';
        },
        function($app,...$parameters)
        {
            echo 'AFTER';
        },
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
    [
        '/resource/{path:.+}',
        ['GET'],
        [
            RoutedServeStatic::class,
            [
                getcwd() . '/demo/static_assets',
                [
                    'css',
                    'js',
                ]
            ]
        ]
    ],
];
