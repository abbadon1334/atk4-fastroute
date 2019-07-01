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

    function handleWithFunction(...$parameters)
    {
        return 'handled with global function';
    }
}