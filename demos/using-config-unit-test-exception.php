<?php

declare(strict_types=1);

include __DIR__.'/bootstrap.php';

$router = new \Abbadon1334\ATKFastRoute\Router(new \atk4\ui\App(['always_run' => false]));
$router->loadRoutes(__DIR__.'/config/routes-exception.php', 'php-inline');
$router->run();
