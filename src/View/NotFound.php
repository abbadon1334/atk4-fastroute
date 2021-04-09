<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use Atk4\Ui\Exception;
use Atk4\Ui\Header;
use Atk4\Ui\View;

class NotFound extends AbstractView
{
    /**
     * @throws Exception
     */
    protected function init(): void
    {
        parent::init();

        Header::addTo($this)->set('REQUESTED ROUTE NOT FOUND');
        View::addTo($this)->set('METHOD : '.$this->request->getMethod());
        View::addTo($this)->set('REQUEST : '.$this->request->getUri());
    }
}
