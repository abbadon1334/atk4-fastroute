<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Demos;

use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;

return [
    [
        '/test',
        ['GET', 'POST'],
        [StandardClass::class, 'handleRequest'],
    ],
    [
        '/',
        ['GET', 'POST'],
        [StandardClass::class, 'handleRequest'],
    ],
    [
        '/testStatic',
        ['GET', 'POST'],
        [StandardClass::class, 'staticHandleRequest'],
    ],
    [
        '/test2',
        ['GET', 'POST'],
        [ATKView::class, ['text' => 'it works']],
    ],
    [
        '/callable',
        ['GET', 'POST'],
        ['\\Abbadon1334\\ATKFastRoute\\Demos\\handleWithFunction'],
        function ($app, ...$parameters): void {
            echo 'BEFORE';
        },
        function ($app, ...$parameters): void {
            echo 'AFTER';
        },
    ],
    [
        '/test-parameters/{id:\d+}/{title}',
        ['GET'],
        [StandardClass::class, 'HandleRequest'],
    ],
    [
        '/test-parameters-static/{id:\d+}/{title}',
        ['GET'],
        [StandardClass::class, 'staticHandleRequest'],
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
                ],
            ],
        ],
    ],
    [
        '/test_before_after',
        ['GET'],
        [
            function (): void {
                echo 'content';
            },
        ],
        function ($app, ...$parameters): void {
            echo 'BEFORE';
        },
        function ($app, ...$parameters): void {
            echo 'AFTER';
        },
    ],
];
