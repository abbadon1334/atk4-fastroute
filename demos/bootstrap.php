<?php

declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

use atk4\ui\Button;
use atk4\ui\Loader;
use atk4\ui\View;

if (!class_exists(ATKView::class)) {
    class StandardClass
    {
        public function handleRequest(...$parameters): void
        {
            echo json_encode($parameters);
        }

        public static function staticHandleRequest(...$parameters): void
        {
            echo json_encode($parameters);
        }
    }

    class ATKView extends View
    {
        public $text;

        public function init(): void
        {
            parent::init();

            $this->set($this->text);

            /** @var Loader $loader */
            $loader = Loader::addTo($this->app);
            $loader->set(function ($l): void {
                $number = rand(1, 100);
                $l->add(['Text', 'random :' . $number]);
            });

            /** @var Button $button */
            $button = Button::addTo($this->app, ['test']);
            $button->on('click', function ($j) use ($loader) {
                return $loader->jsReload();
            });
        }

        public function onRoute(...$parameters)
        {
            $this->set('pass_on_route');
        }
    }

    function handleWithFunction(...$parameters)
    {
        return 'handled with global function';
    }
}
