<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\View;

class NotFound extends View
{
    public function init(): void
    {
        parent::init();

        $this->add('Header')->set('REQUESTED ROUTE NOT FOUND');
        $this->add('View')->set('METHOD : '.getenv('REQUEST_METHOD'));
        $this->add('View')->set('REQUEST : '.getenv('REQUEST_URI'));
    }
}
