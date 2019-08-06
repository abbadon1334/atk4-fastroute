<?php

declare(strict_types=1);

use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;

return [
    [
        '/test',
        ['GET', 'POST'],
        ['StandardClass', 'handleRequest'],
    ],
    [
        '/',
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
                getcwd().'/demo/static_assets',
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
        [function (): void {
            echo 'content';
        }],
        function ($app, ...$parameters): void {
            echo 'BEFORE';
        },
        function ($app, ...$parameters): void {
            echo 'AFTER';
        },
    ],
];
