<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use Atk4\Ui\Header;
use Atk4\Ui\View;

class MethodNotAllowed extends AbstractView
{
    protected array $_allowed_methods = [];

    protected function init(): void
    {
        parent::init();

        Header::addTo($this)->set('Method not Allowed');
        View::addTo($this)->set('METHOD : '.$this->request->getMethod());
        View::addTo($this)->set('REQUEST : '.$this->request->getUri());
        View::addTo($this)->set('ALLOWED METHDOS :'.implode(', ', $this->_allowed_methods));
    }
}
