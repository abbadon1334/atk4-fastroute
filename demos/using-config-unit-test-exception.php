<?php

declare(strict_types=1);

use Abbadon1334\ATKFastRoute\Router;
use Atk4\Ui\App;

include __DIR__.'/bootstrap.php';

$router = new Router(new App(['always_run' => false]));
$router->loadRoutes(__DIR__.'/config/routes-exception.php', 'php');
$router->run();
