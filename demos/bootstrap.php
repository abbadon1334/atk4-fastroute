<?php

declare(strict_types=1);

ini_set('display_errors', 1);

include __DIR__.'/../vendor/autoload.php';

use atk4\ui\View;

if (!class_exists(ATKView::class)) {
    class StandardClass {
        public function handleRequest(...$parameters): void {
            echo json_encode($parameters);
        }

        public static function staticHandleRequest(...$parameters): void {
            echo json_encode($parameters);
        }
    }

    class ATKView extends View {
        public $text;

        public function init(): void {
            parent::init();

            $this->set($this->text);

            /** @var Loader $loader */
            $loader = $this->app->add('Loader');
            $loader->set(function ($l) use ($loader): void {
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

    function handleWithFunction(...$parameters) {
        return 'handled with global function';
    }
}
