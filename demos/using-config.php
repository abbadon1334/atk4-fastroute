<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Demos;

use Abbadon1334\ATKFastRoute\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \Atk4\Ui\App $app */
require __DIR__ . '/init-app.php';

$router = new Router($app);
$router->loadRoutes(__DIR__ . '/config/routes.php', 'php');
$router->run();
