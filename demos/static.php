<?php

include __DIR__.'/bootstrap.php';

use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;
use Abbadon1334\ATKFastRoute\Router;
use atk4\ui\App;

$router = new Router(new App(['always_run' => false]));
//$router->setBaseDir('/'); // added only for coverage in unit test
$router->addRoute(
    '/assets/{path:.+}',
    ['GET'],
    new RoutedServeStatic(
        __DIR__.'/static_assets',
        [
            'css',
        ]
    )
);

$router->run();
