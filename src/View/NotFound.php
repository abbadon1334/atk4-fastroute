<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\Exception;
use atk4\ui\Header;
use atk4\ui\View;

class NotFound extends AbstractView
{
    /**
     * @throws Exception
     */
    public function init(): void
    {
        parent::init();

        Header::addTo($this)->set('REQUESTED ROUTE NOT FOUND');
        View::addTo($this)->set('METHOD : ' . $this->request->getMethod());
        View::addTo($this)->set('REQUEST : ' . $this->request->getUri());
    }
}
