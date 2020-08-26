<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\Exception;
use atk4\ui\Header;
use atk4\ui\View;

class MethodNotAllowed extends AbstractView
{
    /**
     * @var array
     */
    protected $_allowed_methods = [];

    /**
     * @throws Exception
     */
    public function init(): void
    {
        parent::init();

        Header::addTo($this)->set('Method not Allowed');
        View::addTo($this)->set('METHOD : ' . $this->request->getMethod());
        View::addTo($this)->set('REQUEST : ' . $this->request->getUri());
        View::addTo($this)->set('ALLOWED METHDOS :' . implode(', ', $this->_allowed_methods));
    }
}
