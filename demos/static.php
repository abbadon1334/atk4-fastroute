<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Demos;

date_default_timezone_set('UTC');

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \Atk4\Ui\App $app */
require __DIR__ . '/init-app.php';

use Abbadon1334\ATKFastRoute\Handler\RoutedServeStatic;
use Abbadon1334\ATKFastRoute\Router;

$router = new Router($app);
//$router->setBaseDir('/'); // added only for coverage in unit test
$router->addRoute(
    '/assets/{path:.+}',
    ['GET'],
    new RoutedServeStatic(
        __DIR__ . '/static_assets',
        [
            'css',
        ]
    )
);

$router->run();
