<?php

declare(strict_types=1);

use Abbadon1334\ATKFastRoute\Router;
use atk4\ui\App;

include __DIR__.'/bootstrap.php';

$router = new Router(new App(['always_run' => false]));
$router->loadRoutes(__DIR__.'/config/routes.php', 'php');
$router->run();
