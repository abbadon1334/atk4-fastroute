<?php

declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

use Atk4\Ui\Button;
use Atk4\Ui\Loader;
use Atk4\Ui\View;

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

        protected function init(): void
        {
            parent::init();

            $this->set($this->text);

            /** @var Loader $loader */
            $loader = Loader::addTo($this->getApp());
            $loader->set(function ($l): void {
                $number = random_int(1, 100);
                $l->add(['Text', 'random :' . $number]);
            });

            /** @var Button $button */
            $button = Button::addTo($this->getApp(), ['test']);
            $button->on('click', function ($j) use ($loader) {
                return $loader->jsReload();
            });
        }

        public function onRoute(...$parameters): void
        {
            $this->set('pass_on_route');
        }
    }

    function handleWithFunction(...$parameters)
    {
        return 'handled with global function';
    }
}
