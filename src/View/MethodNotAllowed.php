<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\View;

class MethodNotAllowed extends View
{
    public function init(): void
    {
        parent::init();

        $this->add('Header')->set('Method not Allowed');
        $this->add('Text')->set('METHOD : '.$_SERVER['REQUEST_METHOD']);
        $this->add('Text')->set('REQUEST : '.$_SERVER['REQUEST_URI']);
    }
}
