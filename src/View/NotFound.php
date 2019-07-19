<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\Exception;

class NotFound extends AbstractView
{
    /**
     * @throws Exception
     */
    public function init(): void
    {
        parent::init();

        $this->add('Header')->set('REQUESTED ROUTE NOT FOUND');
        $this->add('View')->set('METHOD : ' . $this->request->getMethod());
        $this->add('View')->set('REQUEST : ' . $this->request->getUri());
    }
}
