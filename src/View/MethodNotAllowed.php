<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use atk4\ui\View;

class MethodNotAllowed extends View
{
    public $_allowed_methods = [];

    public function init(): void
    {
        parent::init();

        $this->add('Header')->set('Method not Allowed');
        $this->add('View')->set('METHOD : '.$_SERVER['REQUEST_METHOD']);
        $this->add('View')->set('REQUEST : '.$_SERVER['REQUEST_URI']);

        $this->add('View')->set('ALLOWED METHDOS :' . implode(', ',$this->_allowed_methods));
    }
}
