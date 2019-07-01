<?php

ini_set('display_errors', 1);

include __DIR__.'/../vendor/autoload.php';

use atk4\ui\View;

if (! class_exists(ATKView::class)) {
    class StandardClass
    {
        public function handleRequest(...$parameters)
        {
            echo json_encode($parameters);
        }

        public static function staticHandleRequest(...$parameters)
        {
            echo json_encode($parameters);
        }
    }

    class ATKView extends View
    {
        public $text;

        public function init()
        {
            parent::init();

            $this->set($this->text);

            /** @var Loader $loader */
            $loader = $this->app->add('Loader');
            $loader->set(function ($l) use ($loader) {
                $number = rand(1, 100);
                $l->add(['Text', 'random :'.$number]);
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