<?php

ini_set('display_errors', 1);

include __DIR__ . '/../vendor/autoload.php';

use Abbadon1334\ATKFastRoute\Handler\CallableHandler;
use Abbadon1334\ATKFastRoute\Handler\MethodHandler;
use Abbadon1334\ATKFastRoute\Handler\UIHandler;
use Abbadon1334\ATKFastRoute\Router;
use atk4\ui\App;
use atk4\ui\View;

if(!class_exists(ATKView::class)) {
    class StandardClass
    {

        function handleRequest()
        {
            return new ATKView(['text' => 'prova']);
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

$app = new App([
//    'fix_incompatible' => false
]);

$app->title = $_SERVER['REQUEST_METHOD'] . ' => ' . $_SERVER['REQUEST_URI'];
$app->initLayout('Generic');
$app->add($router = new Router());

$router->setBaseDir('/nemesi/atk4-fastroute/demos');
$router->addRoute(
    ['GET','POST'],
    '/test',
    new MethodHandler(StandardClass::class, 'handleRequest')
);

$router->addRoute(
    ['GET','POST'],
    '/test2',
    new UIHandler(ATKView::class, ['text' => 'it works'])
);

$router->addRoute(
    ['GET','POST'],
    '/callable',
    new CallableHandler(function(...$parameters) {
        echo 'test callable';
    })
);
