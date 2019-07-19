<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\Exception;

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

        $this->add('Header')->set('Method not Allowed');
        $this->add('View')->set('METHOD : ' . $this->request->getMethod());
        $this->add('View')->set('REQUEST : ' . $this->request->getUri());

        $this->add('View')->set('ALLOWED METHDOS :' . implode(', ', $this->_allowed_methods));
    }
}
