<?php

ini_set('display_errors', 1);

include __DIR__ . '/../vendor/autoload.php';

use Abbadon1334\ATKFastRoute\Handler\RoutedCallable;
use Abbadon1334\ATKFastRoute\Handler\RoutedMethod;
use Abbadon1334\ATKFastRoute\Handler\RoutedUI;
use Abbadon1334\ATKFastRoute\Router;
use atk4\ui\App;
use atk4\ui\View;

if(!class_exists(ATKView::class)) {

    class StandardClass
    {

        function handleRequest(...$parameters)
        {
            echo 'test';
        }
    }

    class ATKView extends View
    {
        public $text;

        function init()
        {
            parent::init();

            $this->set($this->text);

            /** @var Loader $loader */
            $loader = $this->app->add('Loader');
            $loader->set(function ($l) use ($loader) {
                $number = rand(1, 100);
                $l->add(['Text', 'random :' . $number]);
            });

            /** @var Button $button */
            $button = $this->app->add(['Button', 'test']);
            $button->on('click', function ($j) use ($loader) {
                return $loader->jsReload();
            });
        }
    }
}

$router = new Router(new App(['always_run' => false]));
//$router->setBaseDir('/nemesi/atk4-fastroute/demos');
$router->addRoute(
    ['GET','POST'],
    '/test',
    new RoutedMethod(StandardClass::class, 'handleRequest')
);

$router->addRoute(
    ['GET','POST'],
    '/test2',
    new RoutedUI(ATKView::class, ['text' => 'it works'])
);

$router->addRoute(
    ['GET','POST'],
    '/callable',
    new RoutedCallable(function(...$parameters) {
        echo 'test callable';
    })
);
