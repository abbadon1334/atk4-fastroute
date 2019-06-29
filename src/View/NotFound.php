<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\View;

class NotFound extends View
{
    public function init(): void
    {
        parent::init();

        $this->add('Header')->set('REQUESTED ROUTE NOT FOUND');
        $this->add('View')->set('METHOD : '.$_SERVER['REQUEST_METHOD']);
        $this->add('View')->set('REQUEST : '.$_SERVER['REQUEST_URI']);
    }
}
