<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Demos;

use Atk4\Ui\Button;
use Atk4\Ui\Loader;
use Atk4\Ui\Text;
use Atk4\Ui\View;

class StandardClass
{
    /**
     * @param mixed ...$parameters
     */
    public function handleRequest(...$parameters): void
    {
        echo json_encode($parameters);
    }

    /**
     * @param mixed ...$parameters
     */
    public static function staticHandleRequest(...$parameters): void
    {
        echo json_encode($parameters);
    }
}

class ATKView extends View
{
    public string $text;

    protected function init(): void
    {
        parent::init();

        $this->set($this->text);

        /** @var Loader $loader */
        $loader = Loader::addTo($this->getApp());
        $loader->set(function ($l): void {
            $number = random_int(1, 100);
            Text::addTo($l)->set('random :'.$number);
        });

        /** @var Button $button */
        $button = Button::addTo($this->getApp(), ['test']);
        $button->on('click', function ($j) use ($loader) {
            return $loader->jsReload();
        });
    }

    /**
     * @param mixed ...$parameters
     */
    public function onRoute(...$parameters): void
    {
        $this->set('pass_on_route');
    }
}

/**
 * @param mixed ...$parameters
 */
function handleWithFunction(...$parameters): string
{
    return 'handled with global function';
}
