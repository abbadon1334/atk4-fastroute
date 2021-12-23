<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use Atk4\Ui\View;
use Psr\Http\Message\RequestInterface;

abstract class AbstractView extends View
{
    /** @var RequestInterface */
    protected $request;

    public function __construct(RequestInterface $request, array $defaults = null)
    {
        $this->request = $request;
        parent::__construct($defaults ?? []);
    }
}
