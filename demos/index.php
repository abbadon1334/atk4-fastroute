<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Demos;

use Abbadon1334\ATKFastRoute\Handler\RoutedCallable;
use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use Abbadon1334\ATKFastRoute\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \Atk4\Ui\App $app */
require __DIR__ . '/init-app.php';

$router = new Router($app);

$router->addRoute(
    '/',
    ['GET', 'POST'],
    new RoutedMethod(StandardClass::class, 'handleRequest')
);
$router->addRoute(
    '/test',
    ['GET', 'POST'],
    new RoutedMethod(StandardClass::class, 'handleRequest')
);

$router->addRoute(
    '/testStatic',
    ['GET', 'POST'],
    new RoutedMethod(StandardClass::class, 'staticHandleRequest')
);

$router->addRoute(
    '/test2',
    ['GET', 'POST'],
    new RoutedUI(ATKView::class, ['text' => 'it works'])
);

$router
    ->addRoute('/test3')
    ->addMethod('GET', 'POST')
    ->setHandler(new RoutedUI(ATKView::class, ['text' => 'it works']));

$router->addRoute(
    '/callable',
    ['GET', 'POST'],
    new RoutedCallable(function (...$parameters): void {
        echo 'test callable';
    })
);

$router->addRoute(
    '/test-parameters/{id:\d+}/{title}',
    ['GET', 'POST'],
    new RoutedMethod(StandardClass::class, 'handleRequest')
);

$router->addRoute(
    '/test-parameters-static/{id:\d+}/{title}',
    ['GET', 'POST'],
    new RoutedMethod(StandardClass::class, 'staticHandleRequest')
);

$router->run();
