<?php

declare(strict_types=1);

include __DIR__.'/bootstrap.php';

use Abbadon1334\ATKFastRoute\Handler\RoutedCallable;
use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use Abbadon1334\ATKFastRoute\Router;
use atk4\ui\App;

$router = new Router(new App(['always_run' => false]));
//$router->setBaseDir('/nemesi/atk4-fastroute/demos');
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
    ->addMethod('GET')
    ->addMethod('POST')
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
