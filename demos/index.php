<?php

include __DIR__.'/bootstrap.php';

use Abbadon1334\ATKFastRoute\Handler\RoutedCallable;
use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use Abbadon1334\ATKFastRoute\Router;
use atk4\ui\App;

$router = new Router(new App(['always_run' => false]));
//$router->setBaseDir('/nemesi/atk4-fastroute/demos');
$router->addRoute(
    ['GET', 'POST'],
    '/test',
    new RoutedMethod(StandardClass::class, 'handleRequest')
);

$router->addRoute(
    ['GET', 'POST'],
    '/test2',
    new RoutedUI(ATKView::class, ['text' => 'it works'])
);

$router->addRoute(
    ['GET', 'POST'],
    '/callable',
    new RoutedCallable(function (...$parameters) {
        echo 'test callable';
    })
);
$router->run();
